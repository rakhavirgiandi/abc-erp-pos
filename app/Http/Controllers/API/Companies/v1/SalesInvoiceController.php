<?php

namespace App\Http\Controllers\API\Companies\v1;

use App\Helpers\NetworkHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Companies\v1\SalesInvoices;
use Illuminate\Http\Request;

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

    public function syncToServer()
    {
        if (!NetworkHelper::isConnected()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada koneksi'
            ], 400);
        }

        SalesInvoices::with([
            'sales_invoice_details',
            'point_histories',
        ])
        ->whereNull('number')
        ->orderBy('id')
        ->where('status', '!=', 'draft')
        ->chunk(100, function ($invoices) {
            $payload = $invoices->toArray();

            $url = config('services.admin_credentials.server_url') . '/api/sync/sync_sales_invoices';
            $response = NetworkHelper::postWithToken($url, $payload);

            if (($response['status'] ?? '') !== 'success') {
                throw new \Exception('Gagal sync ke server');
            }

            foreach ($response['data'] ?? [] as $row) {
                SalesInvoices::where('id', $row['local_id'])->update([
                    'number' => $row['number']
                ]);
            }
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Sync selesai'
        ]);
    }
}
