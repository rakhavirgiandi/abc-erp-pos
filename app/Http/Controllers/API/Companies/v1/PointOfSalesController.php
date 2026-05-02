<?php

namespace App\Http\Controllers\API\Companies\v1;

use App\Http\Controllers\Controller;
use App\Models\Companies\v1\BankAccounts;
use App\Models\Companies\v1\Branches;
use App\Models\Companies\v1\ContactGroupPointRules;
use App\Models\Companies\v1\ContactGroups;
use App\Models\Companies\v1\Contacts;
use App\Models\Companies\v1\Currencies;
use App\Models\Companies\v1\PointHistories;
use App\Models\Companies\v1\RewardPoints;
use App\Models\Companies\v1\SalesInvoices;
use App\Models\Companies\v1\User as CentralUser;
use App\Models\Companies\v1\Warehouses;
use Carbon\Carbon;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Native\Desktop\Facades\System;

class PointOfSalesController extends Controller
{
    //
    public function payment (Request $request)
    {
        $params = $request->all();

        $warehouse = Warehouses::where('id', '=', config('user.warehouse_id'))->first();
        $branch = Branches::where('id', '=', config('user.branch_id'))->first();
        $currency = Currencies::where('id', '=', config('general_settings.default_currency'))->first();

        
        if (isset($params['id']) && $params['id']) {
            $sales_invoice = SalesInvoices::where('id', '=', $params['id'])->first();
            $params['date'] = $sales_invoice->date;
            } else {
                $params['date'] = now();
                }
                
        $bank_account = null;

        if (isset($params['bank_account_id']) && $params['bank_account_id']) {
            $bank_account = BankAccounts::where('id', $params['bank_account_id'])->first();
        } else {
            $bank_account = BankAccounts::where('id', config('general_settings.default_cash'))->first();
        }

        if (!$bank_account) {
            return response()->json([
                'status' => 'error',
                'message' => 'Metode Pembayaran Tidak Ditemukan'
            ], 404);
        }

        $params['payment_type'] = 'cash';
        $params['warehouse_id'] = $warehouse ? $warehouse['id'] : config('general_settings.default_warehouse');
        $params['warehouse_name'] = $warehouse ? $warehouse['name'] : config('general_settings.warehouse_name');
        $params['branch_id'] = $branch ? $branch['id'] : config('general_settings.default_branch');
        $params['branch_name'] = $branch ? $branch['name'] : config('general_settings.branch_name');
        $params['coa_cash'] = $bank_account['coa'];
        $params['bank_account_id'] = $bank_account['id'];
        $params['currency_id'] = config('general_settings.default_currency');
        $params['currency_name'] = config('general_settings.currency_name');
        $params['exchange_rate'] = 1;
        $params['project_id'] = config('general_settings.default_project');
        $params['project_name'] = config('general_settings.project_name');
        $params['down_payment_amount'] = 0;
        $params['discount_percentage'] = 0;
        $params['created_by'] = config('user.id');

        if (isset($params['is_draft']) && $params['is_draft']) {
            unset($params['is_draft']);
            $params['status'] = 'draft';
        } else {
            $params['status'] = 'paid';
        }

        return SalesInvoices::createPOSTransaction($params);
    }

    public function login (Request $request)
    {   
        $credentials = $request->validate([
            'password' => 'required|string',
            'email' => 'required|string'
        ]);
        
        $user = CentralUser::where('email', $request->email)->first();
        
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid password.'
            ], 401);
        }

        return response()->json(['status' => 'success']);
    }

    public function getRefNumber (Request $request)
    {   
        $now = Carbon::now();
        $branch_id = config('user.branch_id') ? config('user.branch_id') : config('general_settings.default_branch');
        $branch = Branches::where('id', '=', $branch_id)->first();
        $user_id = config('user.id');
        $prefix = 'POS';

        if (!$branch) {
            return response()->json([
                'status' => 'success',
                'message' => 'Your account is not linked to an active branch.'
            ], 404);
        }

        $month = $now->month;
        $year = $now->year;
        $date = $now->day;
        $prefix .= explode('-', $branch['code'])[0];
        $prefix .= str_pad($user_id, 3, "0", STR_PAD_LEFT);
        $prefix .= substr($year, -2) . str_pad($month, 2, '0', STR_PAD_LEFT) . str_pad($date, 2, '0', STR_PAD_LEFT);
        $data = SalesInvoices::where('created_by', $user_id)->where('branch_id', $branch_id)->where('ref_number', 'ilike', $prefix.'%')->orderBy('ref_number', 'DESC')->first();

        if (!$data) {
            $prefix .= str_pad($user_id, 5, "0", STR_PAD_LEFT);
        } else {
            $repeat = true;
            $last = substr($data['ref_number'], -5);
            $last = ++$last;

            $new = str_pad($last, 5, "0", STR_PAD_LEFT);

            while ($repeat)
            {
                $data = SalesInvoices::where('ref_number', $prefix . $new)->first();

                if ($data == null) {
                    $repeat = false;
                    $prefix .= str_pad($new, 5, "0", STR_PAD_LEFT);
                } else {
                    $new = $prefix .= str_pad(++$new, 5, "0", STR_PAD_LEFT);
                }
            }
        }

        return response()->json(['ref_number' => $prefix]);
    }

    public function discountPointExchange (Request $request) 
    {   
        $params = $request->all();

        if (empty($params['point']) || empty($params['total_payment'])) {
            return response()->json(['total_discount' => 0]);
        }

        $point = $params['point'];
        $total_payment = $params['total_payment'];


        $data = RewardPoints::where('total_point', '=', $point)->where('is_active', 1)->first();
        $point_exchange = $params['point'];
        $total_payment = floatval($params['total_payment']);

        if (!$data) {
            $data = RewardPoints::where('total_point', '=', 1)->where('discount_type', '=', 'amount')->where('benefit_type', '=', 'discount')->where('discount_type', '=', 'amount')->whereRaw('? % total_point = 0', [$params['point']])->where('is_active', 1)->first();

            $discount_amount = $data->discount_amount * $point_exchange;
        } else {
            $discount_amount = $data->discount_amount;
        }

        if ($data->discount_type == 'percentage') {
            $discount_amount = $total_payment * ($data->discount_percentage / 100);
        }

        $total_discount = $total_payment - $discount_amount;
    
        return response()->json(['total_discount' => $total_discount]);
    }

    public function printerConnected (Request $request)
    {   

        $printers = System::printers();
        
        if (!$request->name) {
            return response()->json($printers);
        }

        $target = $request->name;

        $printer = collect($printers)->first(function ($p) use ($target) {
            return stripos($p, $target) !== false;
        });

        return $printer;
    }

    public function printReceipt (Request $request, $number)
    {
        $find_sales_invoice = SalesInvoices::where('ref_number', '=', $number)->first();
        
        if (!$find_sales_invoice) {
            return response()->json(['status' => 'error', 'message' => 'Receipt Not found'], 404);
        }

        $data = SalesInvoices::getById($find_sales_invoice['id'])->original;

        $paper_size = '58';
        
        if ($request->paper_size) {
            $paper_size = $request->paper_size;
        }

        function format_amount($n) {
            return fmod($n, 1) == 0
                ? number_format($n, 0, ',', '.')
                : number_format($n, 2, ',', '.');
        }

        function col($text, $width, $align = 'left') {
            $text = (string)$text;

            if (strlen($text) > $width) {
                $text = substr($text, 0, $width);
            }

            return $align === 'right'
                ? str_pad($text, $width, ' ', STR_PAD_LEFT)
                : str_pad($text, $width, ' ', STR_PAD_RIGHT);
        }

        try {

            $paper = $paper_size;

            if ($paper == 58) {
                $width = 32;
                $col_qty = 5;
                $col_unit = 5;
                $col_price = 6;
                $col_disc = 6;
                $col_total = 10;
            } else if ($paper == 75) {
                $width = 42;
                $col_qty = 4;
                $col_unit = 6;
                $col_price = 8;
                $col_disc = 7;
                $col_total = 17;
            } else {
                $width = 48;
                $col_qty = 4;
                $col_unit = 6;
                $col_price = 10;
                $col_disc = 8;
                $col_total = 20;
            }

            $line = str_repeat('-', $width);

            if (!config('local_user_settings.pos_printer_selected_printer')) {
                return response()->json(['status' => 'error', 'message' => 'Printer Not found'], 404);
            }

            $connector = new WindowsPrintConnector(config('local_user_settings.pos_printer_selected_printer'));

            if (!$connector) {
                return response()->json(['status' => 'error', 'message' => 'Printer Not found'], 404);
            }

            $printer = new Printer($connector);
 
            $printer->initialize();

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text(config('general_settings.company_name') . "\n");

            $printer->setEmphasis(false);
            $printer->text(config('general_settings.company_address') . "\n");
            $printer->text(config('general_settings.company_phone') . "\n");

            $printer->text($line . "\n");

            // ================= META =================
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("No   : {$data['ref_number']}\n");
            $printer->text("Kasir: {$data['created_by_name']}\n");
            $printer->text("Tgl  : " . date('d/m/Y H:i:s', strtotime($data['created_at'])) . "\n");
            $printer->text("Cust : {$data['customer_name']}\n");

            $printer->text($line . "\n");

            // ================= TABLE HEADER =================
            $printer->setEmphasis(true);
            $printer->text(
                col("Qty", $col_qty) .
                col("Unit", $col_unit) .
                col("Harga", $col_price, 'right') .
                col("Disc", $col_disc, 'right') .
                col("Total", $col_total, 'right') . "\n"
            );
            $printer->setEmphasis(false);

            $printer->text($line . "\n");

            // ================= GROUPING =================
            $sales_invoice_details = [];

            foreach ($data['sales_invoice_details'] as $item) {
                if (isset($sales_invoice_details[$item['product_id']])) {
                    $sales_invoice_details[$item['product_id']]['child'][] = $item;
                } else {
                    $item['child'] = [];
                    $sales_invoice_details[$item['product_id']] = $item;
                }
            }

            // ================= ITEMS =================
            foreach ($sales_invoice_details as $item) {

                $price = floatval($item['unit_price']);
                $qty = floatval($item['qty']);

                $disc = $item['discount_amount'] > 0
                    ? ($item['discount_type'] == 'percentage'
                        ? $price * ($item['discount_amount'] / 100)
                        : $item['discount_amount'])
                    : 0;

                $subtotal = ($price * $qty) - $disc;

                // Nama produk (auto wrap)
                $printer->setEmphasis(true);
                $printer->text(wordwrap($item['product_name'], $width, "\n", true) . "\n");
                $printer->setEmphasis(false);

                // Row utama
                $printer->text(
                    col($qty, $col_qty) .
                    col($item['unit_name'], $col_unit) .
                    col(format_amount($price), $col_price, 'right') .
                    col("-" . format_amount($disc), $col_disc, 'right') .
                    col(format_amount($subtotal), $col_total, 'right') . "\n"
                );

                if (!empty($item['note'])) {
                    $printer->text("  " . wordwrap($item['note'], $width - 2, "\n  ") . "\n");
                }

                // CHILD
                foreach ($item['child'] as $child) {

                    $price = floatval($child['unit_price']);
                    $qty = floatval($child['qty']);

                    $disc = $child['discount_amount'] > 0
                        ? ($child['discount_type'] == 'percentage'
                            ? $price * ($child['discount_amount'] / 100)
                            : $child['discount_amount'])
                        : 0;

                    $subtotal = ($price * $qty) - $disc;

                    $printer->text(
                        col($qty, $col_qty) .
                        col($child['unit_name'], $col_unit) .
                        col(format_amount($price), $col_price, 'right') .
                        col("-" . format_amount($disc), $col_disc, 'right') .
                        col(format_amount($subtotal), $col_total, 'right') . "\n"
                    );

                    if (!empty($child['note'])) {
                        $printer->text("  " . wordwrap($child['note'], $width - 2, "\n  ") . "\n");
                    }
                }
            }

            $printer->text($line . "\n");

            // ================= SUMMARY =================
            $printer->text(sprintf("%-".($width-13)."s %12s\n", "Subtotal", format_amount($data['subtotal'])));
            $printer->text(sprintf("%-".($width-13)."s %12s\n", "Diskon", "-" . format_amount($data['discount_amount'])));

            $printer->setEmphasis(true);
            $printer->text(sprintf("%-".($width-13)."s %12s\n", "Total", format_amount($data['total'])));
            $printer->setEmphasis(false);

            $printer->text(sprintf("%-".($width-13)."s %12s\n", "Bayar", format_amount($data['total_payment'])));
            $printer->text(sprintf("%-".($width-13)."s %12s\n", "Kembali", format_amount($data['total_change'])));

            $printer->text($line . "\n");

            // ================= FOOTER =================
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text(wordwrap(config('general_settings.pos_receipt_footer_text'), $width) . "\n");

            $printer->feed(2);
            $printer->cut();
            $printer->close();

        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'Tolong cek ulang pengaturan printer anda'], 500);
        }

    }
}
