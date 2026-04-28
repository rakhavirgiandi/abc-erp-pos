<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SalesInvoices;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PointOfSalesController extends Controller
{
    //
    public function cashier()
    {
        $title = __('language.cashier');

        return view('pos.cashier.index', compact('title'));
    }

    public function printReceipt (Request $request, $number)
    {
        $find_sales_invoice = SalesInvoices::where('ref_number', '=', $number)->first();
        
        if (!$find_sales_invoice) {
            abort(404);
        }

        $paper_size = '58';
        
        if ($request->paper_size) {
            $paper_size = $request->paper_size;
        }

        $data = SalesInvoices::getById($find_sales_invoice['id'])->original;

        return view('pos.print.receipt', ['data' => $data->toArray(), 'paper_size' => $paper_size]);

    }

    public function settings (Request $request)
    {
        return view('pos.settings', ['title' => 'Settings']);
    }

    public function authorize (Request $request)
    {
        $request->session()->put('_is_access_to_pos', true);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
            ], 200);
        }
        return redirect('/pos/cashier');
    }

    public function logout (Request $request)
    {
        $request->session()->put('_is_access_to_pos', false);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
            ], 200);
        }
        return redirect('/home');
    }
}
