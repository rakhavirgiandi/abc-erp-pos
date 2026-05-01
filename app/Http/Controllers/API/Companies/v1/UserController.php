<?php

namespace App\Http\Controllers\API\Companies\v1;

use App\Helpers\NetworkHelper;
use App\Http\Controllers\Controller;
use App\Models\Companies\v1\User;
use App\Models\Companies\v1\Users;
use App\Models\User as CentralUser;
use App\Models\UserCompanies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function get(Request $request, $id = null)
    {
        $params = $request->all();

        if ($id != null) {
            $res = Users::getById($id, $params, $request);
        } else if (isset($params['all']) && $params['all']) {
            $res = Users::getAllResult($params, $request);
        } else {
            $res = Users::getPaginatedResult($params, $request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return Users::createOrUpdate($params, $request->method(), $request);
    }

    public function put(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Users::createOrUpdate($params, $request->method(), $request);
    }

    public function patch(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Users::createOrUpdate($params, $request->method(), $request);
    }

    public function delete(Request $request, $id)
    {
        $params = $request->all();

        return Users::deleteById($id, $params, $request);
    }

    public function approve(Request $request, $id)
    {
        $params = $request->all();

        return Users::approveById($id, $params, $request);
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('sanctum')->user();

        $columns = [
            'users.id'
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

        $res = Users::datatables($start, $limit, $order, $dir, $search, $filter);

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
            $res = Users::getPaginatedResult($params, $request);

            return response()->json([
                'status' => 'offline',
                'message' => 'Tidak ada koneksi, menggunakan data lokal',
                'data' => $res
            ]);
        }

        $page = 1;
        $perPage = 500;

        do {
            $url = config('services.admin_credentials.server_url') . "/api/v1/users?page={$page}&per_page={$perPage}&is_simple=true";
            $result = NetworkHelper::curlWithToken($url);

            $rows = $result['data'] ?? [];

            if (empty($rows)) break;

            DB::connection('pgsql_companies')->beginTransaction();

            try {
                $emails = collect($rows)->pluck('email')->filter()->unique()->toArray();
                $existing_users = Users::whereIn('email', $emails)->get()->keyBy('email');

                $insert = [];

                foreach ($rows as $row) {
                    if (!isset($row['email'])) continue;

                    $user = $existing_users[$row['email']] ?? null;

                    if ($user) {
                        unset($row['id']);

                        if (empty($row['password'])) {
                            unset($row['password']);
                        }

                        if (isset($row['password']) && !str_starts_with($row['password'], '$2y$')) {
                            $row['password'] = bcrypt($row['password']);
                        }

                        $user->update($row);

                    } else {
                        if (isset($row['password']) && !str_starts_with($row['password'], '$2y$')) {
                            $row['password'] = bcrypt($row['password']);
                        }

                        $row['username'] = $row['email'];

                        $insert[] = $row;
                    }
                }

                if (!empty($insert)) {
                    foreach ($insert as $row) {
                        $customer = CentralUser::where('email', $row['email'])->first();

                        if (!$customer) {
                            $customer = CentralUser::create([
                                'id' => Str::orderedUuid()->toString(),
                                'name' => $row['name'],
                                'email' => $row['email'],
                                'phone' => $row['phone'] ?? null,
                                'password' => $row['password'],
                            ]);
                        }

                        UserCompanies::firstOrCreate([
                            'user_id' => $customer->id,
                            'company_id' => config('company_id'),
                        ], [
                            'id' => Str::orderedUuid()->toString(),
                            'type' => 'member'
                        ]);
                    }

                    Users::insert($insert);
                }

                DB::connection('pgsql_companies')->statement("SELECT SETVAL('users_id_seq', COALESCE((SELECT MAX(id) + 1 FROM users), 1))");
                DB::connection('pgsql_companies')->commit();

            } catch (\Exception $e) {
                DB::connection('pgsql_companies')->rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal sync bank',
                    'error' => $e->getMessage()
                ], 500);
            }

            $page++;

        } while ($page <= ($result['nav']['totalPage'] ?? 1));

        return response()->json([
            'status' => 'success',
            'message' => 'Sync bank berhasil',
        ]);
    }
}
