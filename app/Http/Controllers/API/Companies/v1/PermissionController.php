<?php

namespace App\Http\Controllers\API\Companies\v1;

use App\Helpers\NetworkHelper;
use App\Http\Controllers\Controller;
use App\Models\Companies\v1\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function get(Request $request, $id = null)
    {
        $params = $request->all();

        if ($id != null) {
            $res = Permissions::getById($id, $params, $request);
        } else if (isset($params['all']) && $params['all']) {
            $res = Permissions::getAllResult($params, $request);
        } else {
            $res = Permissions::getPaginatedResult($params, $request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return Permissions::createOrUpdate($params, $request->method(), $request);
    }

    public function put(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Permissions::createOrUpdate($params, $request->method(), $request);
    }

    public function patch(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Permissions::createOrUpdate($params, $request->method(), $request);
    }

    public function delete(Request $request, $id)
    {
        $params = $request->all();

        return Permissions::deleteById($id, $params, $request);
    }

    public function approve(Request $request, $id)
    {
        $params = $request->all();

        return Permissions::approveById($id, $params, $request);
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('sanctum')->user();

        $columns = [
            'permissions.id'
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

        $res = Permissions::datatables($start, $limit, $order, $dir, $search, $filter);

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

    public static function syncToLocal(Request $request)
    {
        if (!NetworkHelper::isConnected()) {
            $params = $request->all();
            $res = Permissions::getPaginatedResult($params, $request);

            return response()->json([
                'status' => 'offline',
                'message' => 'Tidak ada koneksi, menggunakan data lokal',
                'data' => $res
            ]);
        }

        $page = 1;
        $perPage = 500;

        do {
            $url = config('services.admin_credentials.server_url') . "/api/v1/permissions?page={$page}&per_page={$perPage}&is_simple=true";
            $result = NetworkHelper::curlWithToken($url);

            $rows = $result['data'] ?? [];

            if (empty($rows)) break;

            DB::connection('pgsql_companies')->beginTransaction();

            try {
                Permissions::upsert(
                    collect($rows)->map(fn($row) => [
                        'id' => $row['id'],
                        'name' => $row['name'],
                        'guard_name' => $row['guard_name'] ?? 'web',
                        'updated_at' => now(),
                    ])->toArray(),
                    ['id'],
                    ['name', 'guard_name', 'updated_at']
                );

                $serverIds = collect($rows)->pluck('id')->toArray();

                Permissions::whereNotIn('id', $serverIds)->delete();


                DB::connection('pgsql_companies')->statement("SELECT SETVAL('permissions_id_seq', COALESCE((SELECT MAX(id) + 1 FROM permissions), 1))");
                DB::connection('pgsql_companies')->commit();

            } catch (\Exception $e) {
                DB::connection('pgsql_companies')->rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal sync permission',
                    'error' => $e->getMessage()
                ], 500);
            }

            $page++;

        } while ($page <= ($result['nav']['totalPage'] ?? 1));

        return response()->json([
            'status' => 'success',
            'message' => 'Sync permission berhasil',
        ]);
    }
}
