<?php

namespace App\Http\Controllers\API\Companies\v1;

use App\Helpers\NetworkHelper;
use App\Http\Controllers\Controller;
use App\Models\Companies\v1\RoleHasPermissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleHasPermissionController extends Controller
{
    public function get(Request $request, $id = null)
    {
        $params = $request->all();

        if ($id != null) {
            $res = RoleHasPermissions::getById($id, $params, $request);
        } else if (isset($params['all']) && $params['all']) {
            $res = RoleHasPermissions::getAllResult($params, $request);
        } else {
            $res = RoleHasPermissions::getPaginatedResult($params, $request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return RoleHasPermissions::createOrUpdate($params, $request->method(), $request);
    }

    public function put(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return RoleHasPermissions::createOrUpdate($params, $request->method(), $request);
    }

    public function patch(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return RoleHasPermissions::createOrUpdate($params, $request->method(), $request);
    }

    public function delete(Request $request, $id)
    {
        $params = $request->all();

        return RoleHasPermissions::deleteById($id, $params, $request);
    }

    public function approve(Request $request, $id)
    {
        $params = $request->all();

        return RoleHasPermissions::approveById($id, $params, $request);
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('sanctum')->user();

        $columns = [
            'role_has_permissions.id'
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

        $res = RoleHasPermissions::datatables($start, $limit, $order, $dir, $search, $filter);

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
            $res = RoleHasPermissions::getPaginatedResult($params, $request);

            return response()->json([
                'status' => 'offline',
                'message' => 'Tidak ada koneksi, menggunakan data lokal',
                'data' => $res
            ]);
        }

        $page = 1;
        $perPage = 500;

        do {
            $url = config('services.admin_credentials.server_url') . "/api/v1/role_has_permissions?page={$page}&per_page={$perPage}&is_simple=true";
            $result = NetworkHelper::curlWithToken($url);

            $rows = $result['data'] ?? [];

            if (empty($rows)) break;

            DB::connection('pgsql_companies')->beginTransaction();

            try {
                $pairs = collect($rows)
                    ->filter(fn($row) => $row['role_id'] != 1)
                    ->map(function ($row) {
                        return [
                            'role_id' => $row['role_id'],
                            'permission_id' => $row['permission_id'],
                        ];
                    });

                $serverKeys = $pairs
                    ->map(fn($r) => $r['role_id'] . '-' . $r['permission_id'])
                    ->toArray();


                $existing = RoleHasPermissions::select('role_id', 'permission_id')
                    ->where('role_id', '!=', 1)
                    ->get();

                $existingKeys = $existing
                    ->map(fn($r) => $r->role_id . '-' . $r->permission_id)
                    ->toArray();

                $insert = $pairs->filter(function ($row) use ($existingKeys) {
                    $key = $row['role_id'] . '-' . $row['permission_id'];
                    return !in_array($key, $existingKeys);
                })->values()->toArray();

                if (!empty($insert)) {
                    RoleHasPermissions::insert($insert);
                }

                $deleteKeys = array_diff($existingKeys, $serverKeys);

                if (!empty($deleteKeys)) {
                    RoleHasPermissions::where('role_id', '!=', 1)
                        ->where(function ($query) use ($deleteKeys) {
                            foreach ($deleteKeys as $key) {
                                [$role_id, $permission_id] = explode('-', $key);

                                $query->orWhere(function ($q) use ($role_id, $permission_id) {
                                    $q->where('role_id', $role_id)
                                    ->where('permission_id', $permission_id);
                                });
                            }
                        })->delete();
                }

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
