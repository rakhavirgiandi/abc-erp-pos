<?php

namespace App\Http\Controllers\API;

use App\Helpers\NetworkHelper;
use App\Http\Controllers\Controller;
use App\Models\UserCompanies;
use Illuminate\Http\Request;

class UserCompanyController extends Controller
{
    public function get(Request $request, $id = null)
    {
        $params = $request->all();

        if ($id != null) {
            $res = UserCompanies::getById($id, $params, $request);
        } else if (isset($params['all']) && $params['all']) {
            if (env('IS_ONPREMISE', false)) {
                $internet_connection = NetworkHelper::isConnected();
                if ($internet_connection) {
                    $url = config('services.admin_credentials.server_url') . "/api/user_companies?all=true";
                    $res = NetworkHelper::curlWithToken($url, true);
                } else {
                    $res = UserCompanies::getAllResult($params, $request);

                    if ($res->original->isEmpty()) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Data Perusahaan Anda belum tersinkron dengan Data Perusahaan Server. Pastikan koneksi internet Anda Aktif untuk melakukan proses sinkron',
                            'data' => [
                                'internet_connection' => $internet_connection,
                                'is_onpremise' => env('IS_ONPREMISE')
                            ]
                        ], 400);
                    }
                }
            } else {
                $res = UserCompanies::getAllResult($params, $request);
            }
        } else {
            $res = UserCompanies::getPaginatedResult($params, $request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return UserCompanies::createOrUpdate($params, $request->method(), $request);
    }

    public function put(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return UserCompanies::createOrUpdate($params, $request->method());
    }

    public function patch(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return UserCompanies::createOrUpdate($params, $request->method());
    }

    public function delete(Request $request, $id)
    {
        $params = $request->all();

        return UserCompanies::deleteById($id, $params, $request);
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('sanctum')->user();

        $columns = [
            0 => 'user_companies.id'
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

        $filter = $request->only(['sDate', 'eDate']);

        $res = UserCompanies::datatables($start, $limit, $order, $dir, $search, $filter);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData = $row;
                $nestedData['action'] = '';
                $nestedData['action'] .= '<div class="actions">';
                $nestedData['action'] .= '<a href="#" class="btn btn-icon btn-warning" id="edit-data" data-id="'.$row['id'].'"><i class="fas fa-pencil-alt"></i></a>';
                $nestedData['action'] .= '&nbsp;';
                $nestedData['action'] .= '<a href="#" class="btn btn-icon btn-danger" id="delete-data" data-id="'.$row['id'].'"><i class="fas fa-trash-alt-o"></i></a>';
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
}
