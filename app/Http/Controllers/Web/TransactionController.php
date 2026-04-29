<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Companies;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function detail($id)
    {
        $title = 'Detail Transaksi';

        return view('transaction.'.__FUNCTION__, [
            'title' => $title,
            'id' => $id
        ]);
    }
}
