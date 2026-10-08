<?php

namespace App\Http\Controllers\API\Companies\v1;

use App\Helpers\ModelHelper;
use App\Helpers\NetworkHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Companies\v1\Roles;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function get(Request $request, $id = null)
    {
        $params = $request->all();

        if ($id != null) {
            $res = Roles::getById($id, $params, $request);
        } else if (isset($params['all']) && $params['all']) {
            $res = Roles::getAllResult($params, $request);
        } else {
            $res = Roles::getPaginatedResult($params, $request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return Roles::createOrUpdate($params, $request->method(), $request);
    }

    public function put(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Roles::createOrUpdate($params, $request->method(), $request);
    }

    public function patch(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Roles::createOrUpdate($params, $request->method(), $request);
    }

    public function delete(Request $request, $id)
    {
        $params = $request->all();

        return Roles::deleteById($id, $params, $request);
    }

    public function approve(Request $request, $id)
    {
        $params = $request->all();

        return Roles::approveById($id, $params, $request);
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('sanctum')->user();

        $columns = [
            'roles.id'
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

        $res = Roles::datatables($start, $limit, $order, $dir, $search, $filter);

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
            $res = Roles::getPaginatedResult($params, $request);

            return response()->json([
                'status' => 'offline',
                'message' => 'Tidak ada koneksi, menggunakan data lokal',
                'data' => $res
            ]);
        }

        // 1. Ambil semua data server dulu (di luar transaksi)
        $page = 1;
        $perPage = 500;
        $serverRows = [];

        do {
            $url = config('services.admin_credentials.server_url') . "/api/v1/roles?page={$page}&per_page={$perPage}&is_simple=true&order[id]=asc";
            $result = NetworkHelper::curlWithToken($url);

            $rows = $result['data'] ?? [];
            if (empty($rows)) break;

            $serverRows = array_merge($serverRows, $rows);
            $page++;
        } while ($page <= ($result['nav']['totalPage'] ?? 1));

        if (empty($serverRows)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal sync role',
                'error' => 'Data role dari server kosong, sync dibatalkan'
            ], 500);
        }

        $now = now();
        $payload = array_map(fn($row) => [
            'id' => $row['id'],
            'name' => $row['name'],
            'guard_name' => $row['guard_name'] ?? 'web',
            'created_at' => $row['created_at'] ?? $now,
            'updated_at' => $now,
        ], $serverRows);

        $serverIds = array_column($payload, 'id');

        // 2. Hapus lokal -> upsert, dalam satu transaksi
        $conn = DB::connection('pgsql_companies');
        $conn->beginTransaction();

        try {
            // hapus dulu supaya tidak bentrok dengan unique (name, guard_name)
            Roles::whereNotIn('id', $serverIds)->delete();

            foreach (array_chunk($payload, 500) as $chunk) {
                Roles::upsert($chunk, ['id'], ['name', 'guard_name', 'updated_at']);
            }

            ModelHelper::reorderPermissionAdmin();

            $conn->commit();

            $conn->statement("SELECT SETVAL('roles_id_seq', COALESCE((SELECT MAX(id) + 1 FROM roles), 1))");
        } catch (\Exception $e) {
            $conn->rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal sync role',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Sync role berhasil',
        ]);
    }
}
