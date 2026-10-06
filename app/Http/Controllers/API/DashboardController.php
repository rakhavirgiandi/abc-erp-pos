<?php

namespace App\Http\Controllers\API;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use App\Models\Companies\v1\Products;
use App\Models\Companies\v1\SalesInvoices;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{   
    private function resolvePreviousRange(string $date_from, string $date_to): array
    {
        $cur_start = Carbon::parse($date_from);
        $cur_end = Carbon::parse($date_to);
 
        $days = $cur_start->diffInDays($cur_end) + 1;
        $prev_end = $cur_start->copy()->subDay();
        $prev_start = $prev_end->copy()->subDays($days - 1);
 
        return [$prev_start->toDateString(), $prev_end->toDateString()];
    }
 
    public function salesMetric (Request $request)  {
        function buildSalesMetric(int|float $current, int|float $previous): array
        {
            $current = (float) $current;
            $previous = (float) $previous;
     
            if ($previous == 0.0) {
                return ['value' => $current, 'difference' => $current, 'difference_type' => 'value'];
            }
     
            return [
                'value' => $current,
                'difference' => round((($current - $previous) / $previous) * 100, 2),
                'difference_type' => 'percentage',
            ];
        }

        $date_from  = $request->input('from_date');
        $date_to  = $request->input('to_date');

        $cur_start = Carbon::parse($date_from)->toDateString();
        $cur_end = Carbon::parse($date_to)->toDateString();
 
        [$prev_start, $prev_end] = $this->resolvePreviousRange($cur_start, $cur_end);
 
        $header = DB::connection('pgsql_companies')->table('sales_invoices')
            ->selectRaw('COALESCE(SUM(total) FILTER (WHERE date BETWEEN ? AND ?), 0) AS current_total', [$cur_start, $cur_end])
            ->selectRaw('COUNT(id) FILTER (WHERE date BETWEEN ? AND ?) AS current_count', [$cur_start, $cur_end])
            ->selectRaw('COALESCE(SUM(total) FILTER (WHERE date BETWEEN ? AND ?), 0) AS previous_total', [$prev_start, $prev_end])
            ->selectRaw('COUNT(id) FILTER (WHERE date BETWEEN ? AND ?) AS previous_count', [$prev_start, $prev_end])
            ->whereNull('deleted_at')
            ->where('sales_invoices.status', '=', 'paid')
            ->where('sales_invoices.is_need_sync', '=', 0)
            ->whereBetween('date', [$prev_start, $cur_end])
            ->first();
 
        $items = DB::connection('pgsql_companies')->table('sales_invoice_details as sid')
            ->join('sales_invoices as si', 'si.id', '=', 'sid.sales_invoice_id')
            ->selectRaw('COALESCE(SUM(sid.qty) FILTER (WHERE si.date BETWEEN ? AND ?), 0) AS current_qty', [$cur_start, $cur_end])
            ->selectRaw('COALESCE(SUM(sid.qty) FILTER (WHERE si.date BETWEEN ? AND ?), 0) AS previous_qty', [$prev_start, $prev_end])
            ->whereNull('si.deleted_at')
            ->whereNull('sid.deleted_at')
            ->where('si.status', '=', 'paid')
            ->where('si.is_need_sync', '=', 0)
            ->whereBetween('si.date', [$prev_start, $cur_end])
            ->first();
 
        $curAvg = $header->current_count > 0 ? $header->current_total / $header->current_count : 0;
        $prevAvg = $header->previous_count > 0 ? $header->previous_total / $header->previous_count : 0;
 
        return response()->json([
            'total_sales' => buildSalesMetric($header->current_total, $header->previous_total),
            'sales_count' => buildSalesMetric($header->current_count, $header->previous_count),
            'items_sold' => buildSalesMetric($items->current_qty, $items->previous_qty),
            'average_sales' => buildSalesMetric($curAvg, $prevAvg),
        ]);
    }

    public function salesOverviewChart(Request $request)
    {   

        $month  = $request->input('month');
        $year  = $request->input('year');

        function resolveRange(?int $month, int $year): array
        {
            if ($month) {
                $from = Carbon::create($year, $month, 1)->startOfMonth();
    
                return [$from, $from->copy()->endOfMonth(), 'day', 'Y-m-d'];
            }
    
            $from = Carbon::create($year, 1, 1)->startOfYear();
    
            return [$from, $from->copy()->endOfYear(), 'month', 'Y-m'];
        }

        [$from, $to, $groupBy, $format] = resolveRange($month, $year);
 
        $rows = DB::connection('pgsql_companies')->table('sales_invoices')
            ->selectRaw('date_trunc(?, date) AS bucket', [$groupBy])
            ->selectRaw('COALESCE(SUM(total), 0) AS total_sales')
            ->selectRaw('COUNT(id) AS sales_count')
            ->whereNull('deleted_at')
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get();

        return [
            'categories' => $rows->map(fn ($row) => Carbon::parse($row->bucket)->format($format))->values(),
            'series' => [
                ['name' => 'Total Penjualan', 'data' => $rows->pluck('total_sales')->map(fn ($v) => (float) $v)->values()],
                ['name' => 'Jumlah Transaksi', 'data' => $rows->pluck('sales_count')->map(fn ($v) => (int) $v)->values()],
            ],
        ];
    }

    public function recentTransactions(Request $request)
    {
        $data = SalesInvoices::
            select([
                'sales_invoices.id as id',
                'sales_invoices.number as number',
                'sales_invoices.updated_at as updated_at',
                'sales_invoices.total as total',
                'cashier.name as cashier_name',
                'customer.name as customer_name',
            ])
            ->join('contacts as customer', 'sales_invoices.customer_id', '=', 'customer.id')
            ->join('users as cashier', 'sales_invoices.created_by', '=', 'cashier.id')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json(['data' => $data]);
    }

    public function  lowStockProduct(Request $request)
    {
        $data = Products::join(DB::raw("
                        (
                            SELECT
                                ph.product_id,
                                SUM(
                                    CASE
                                        WHEN ph.type = 'IN' THEN ph.qty
                                        WHEN ph.type = 'OUT' THEN -ph.qty
                                        ELSE 0
                                    END
                                ) AS qty
                            FROM product_histories ph
                            WHERE ph.deleted_at IS NULL
                            GROUP BY ph.product_id
                        ) as inventories
                    "),'inventories.product_id', '=',  'products.id')->get();
        
        dd($data);
    }
}
