<?php

namespace App\Http\Controllers\Web;

use App\Helpers\DuitkuService;
use App\Helpers\GlobalHelper;
// use App\Helpers\IpaymuService;
use App\Http\Controllers\Controller;
use App\Models\Companies;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $title = 'Pilih Perusahaan';

        return view('choose_company.'.__FUNCTION__, [
            'title' => $title
        ]);
    }

    public function edition($company_id)
    {
        $title = 'Pilih Edisi';

        return view('subscription.'.__FUNCTION__, [
            'title' => $title,
            'company_id' => $company_id
        ]);
    }

    public function period($company_id, $edition_id)
    {
        $title = 'Pilih Periode';

        return view('subscription.'.__FUNCTION__, [
            'title' => $title,
            'company_id' => $company_id,
            'edition_id' => $edition_id
        ]);
    }

    public function paymentMethod($company_id, $edition_id, $period_id, Request $request)
    {
        $params = $request->all();

        $title = 'Pilih Metode Pembayaran';

        $payment_methods = [];

        $payment_gateway_service = new DuitkuService();

        $payment_data = $payment_gateway_service->getPaymentMethod($params['total']);

        if (isset($payment_data['status']) && $payment_data['status'] == 'success') {
            foreach($payment_data['data']['paymentFee'] as $row) {
                if ($row['paymentMethod'] == 'VC') {
                    $payment_methods['credit_card'][] = $row;
                } else {
                    $payment_methods['virtual_account'][] = $row;
                }
            }
        }

        return view('subscription.'.GlobalHelper::camelToSnake(__FUNCTION__), [
            'title' => $title,
            'company_id' => $company_id,
            'edition_id' => $edition_id,
            'period_id' => $period_id,
            'payment_methods' => $payment_methods
        ]);
    }
}
