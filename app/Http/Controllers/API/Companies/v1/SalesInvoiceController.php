<?php

namespace App\Http\Controllers\API\Companies\v1;

use App\Helpers\NetworkHelper;
use Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Companies\v1\SalesInvoices;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class SalesInvoiceController extends Controller
{
    public function get(Request $request, $id = null)
    {
        $params = $request->all();

        if ($id != null) {
            $res = SalesInvoices::getById($id, $params, $request);
        } else if (isset($params['all']) && $params['all']) {
            $res = SalesInvoices::getAllResult($params, $request);
        } else {
            $res = SalesInvoices::getPaginatedResult($params, $request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return SalesInvoices::createOrUpdate($params, $request->method(), $request);
    }

    public function put(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return SalesInvoices::createOrUpdate($params, $request->method(), $request);
    }

    public function patch(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return SalesInvoices::createOrUpdate($params, $request->method(), $request);
    }

    public function delete(Request $request, $id)
    {
        $params = $request->all();

        return SalesInvoices::deleteById($id, $params, $request);
    }

    public function approve(Request $request, $id)
    {
        $params = $request->all();

        return SalesInvoices::approveById($id, $params, $request);
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('sanctum')->user();

        $columns = [
            'sales_invoices.id'
        ];

        $dataOrder = [];

        $limit = $request->length;

        $start = $request->start;

        foreach ($request->order as $row) {
            $nestedOrder['column'] = $columns[$row['column']];
            $nestedOrder['dir'] = $row['dir'];

            $dataOrder[] = $nestedOrder;
        }

        $order = $dataOrder;

        $dir = $request->order[0]['dir'];

        $search = $request->search['value'];

        $filter = $request->filter;

        $res = SalesInvoices::datatables($start, $limit, $order, $dir, $search, $filter);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData = $row;
                $nestedData['action'] = '';
                $nestedData['action'] .= '<div class="actions">';
                $nestedData['action'] .= '<a href="#" class="btn btn-icon btn-warning" id="edit-data" data-id="'.$row['id'].'"><i class="fa fa-pencil"></i></a>';
                $nestedData['action'] .= '&nbsp;';
                $nestedData['action'] .= '<a href="#" class="btn btn-icon btn-danger" id="delete-data" data-id="'.$row['id'].'"><i class="fa fa-trash-o"></i></a>';
                $nestedData['action'] .= '</div>';

                $data[] = $nestedData;
            }
        }

        $json_data = [
            'draw'  => intval($request->draw),
            'recordsTotal'  => intval($res['totalData']),
            'recordsFiltered' => intval($res['totalFiltered']),
            'data'  => $data,
            'order' => $order
        ];

        return json_encode($json_data);
    }

    public function syncToServer(Request $request, $id)
    {
        if (!NetworkHelper::isConnected()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada koneksi'
            ], 400);
        }

        if (!$id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi tidak ditemukan'
            ], 400);
        }
        
        $lock = Cache::lock('sync_sales_invoice_to_server:' . $id, 120);
        if (!$lock->get()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi sedang disinkronkan'
            ], 409);
        }

        try {
            $invoice = SalesInvoices::with(['sales_invoice_details', 'point_histories'])
                ->where('id', $id)
                ->where('is_need_sync', 1)
                ->where('status', '!=', 'draft')
                ->first();

            if (!$invoice) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaksi tidak ditemukan atau masih draft'
                ], 404);
            }

            if (!$invoice->is_need_sync) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Transaksi sudah tersinkron',
                    'number' => $invoice->number,
                ]);
            }

            if (!$invoice->uuid) {
                $invoice->update([
                    'uuid' => Str::uuid(),
                ]);

                $invoice = SalesInvoices::with(['sales_invoice_details', 'point_histories'])
                    ->where('id', $id)
                    ->first();
            }

            $url = config('services.admin_credentials.server_url') . '/api/v1/sync/sync_sales_invoices';

            try {
                $response = NetworkHelper::postWithToken($url, $invoice->toArray());
            } catch (\Throwable $e) {
                $response = ['status' => 'error', 'message' => $e->getMessage()];
            }

            $confirmed = collect($response['data'] ?? [])
                ->first(fn ($row) => ($row['local_id'] ?? null) == $invoice->id && !empty($row['number']));

            if (($response['status'] ?? 'error') === 'success' && $confirmed) {
                $invoice->update([
                    'number' => $confirmed['number'],
                    'is_need_sync' => $confirmed['is_need_sync'] ?? 0,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Sync berhasil',
                    'number' => $confirmed['number'],
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => $response['message'] ?? 'Gagal sync ke server',
                'errors' => $response['errors'] ?? [],
            ], 500);
        } finally {
            $lock->release();
        }
    }
}
