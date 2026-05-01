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
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
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
}
