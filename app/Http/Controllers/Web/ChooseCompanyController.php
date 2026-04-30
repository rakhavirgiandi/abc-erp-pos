<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Companies;
use App\Models\Subscriptions;
use Illuminate\Http\Request;

class ChooseCompanyController extends Controller
{
    public function index()
    {
        $title = 'Choose Company';

        return view('choose_company.'.__FUNCTION__, [
            'title' => $title
        ]);
    }

    public function openDatabase(Request $request)
    {
        $params = $request->all();

        Companies::databaseStarter($params, $request);

        $subscription = Subscriptions::where('company_id', $params['company_id'])->first();

        if ($subscription) {
            if ($subscription['status'] == 'Suspend') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Masa berlaku Aplikasi Mandep anda sudah habis, harap segera melakukan pembayaran untuk melanjutkan penggunaan Aplikasi Mandep. Jika dalam jangka waktu 7 hari tidak ada pembayaran, maka data anda akan terhapus oleh Sistem secara otomatis. Jika Anda mengalami kesulitan silahkan kontak admin melalui WA/Telp. di 0821 2132 8828 untuk konfirmasi Pelatihan Online maupun Offline'
                ]);
            } else if ($subscription['status'] == 'Awaiting Payment') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Harap segera selesaikan pembayaran Anda. Dengan cara klik Aksi -> Invoice -> Klik Tombol hijau pada data paling bawah. Jika Anda mengalami kesulitan silahkan kontak admin melalui WA/Telp. di 0821 2132 8828 untuk konfirmasi Pelatihan Online maupun Offline'
                ]);
            } else if ($subscription['status'] == 'Not Active') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Status data Anda telah Not Active. Data anda telah dihapus dari database kami.'
                ]);
            }
        }

        $request->session()->put('_company_id', $params['company_id']);

        return response()->json([
            'status' => 'success'
        ]);
    }
}
