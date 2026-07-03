<?php

namespace App\Http\Controllers\API\Companies\v1;

use App\Helpers\NetworkHelper;
use App\Http\Controllers\Controller;
use App\Models\Companies\v1\PointHistories;
use DB;
use Illuminate\Http\Request;

class PointHistoryController extends Controller
{
    public function get(Request $request, $id = null)
    {
        $params = $request->all();

        if ($id != null) {
            $res = PointHistories::getById($id, $params, $request);
        } else if (isset($params['all']) && $params['all']) {
            $res = PointHistories::getAllResult($params, $request);
        } else {
            $res = PointHistories::getPaginatedResult($params, $request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return PointHistories::createOrUpdate($params, $request->method(), $request);
    }

    public function put(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return PointHistories::createOrUpdate($params, $request->method(), $request);
    }

    public function patch(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return PointHistories::createOrUpdate($params, $request->method(), $request);
    }

    public function delete(Request $request, $id)
    {
        $params = $request->all();

        return PointHistories::deleteById($id, $params, $request);
    }

    public function approve(Request $request, $id)
    {
        $params = $request->all();

        return PointHistories::approveById($id, $params, $request);
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('sanctum')->user();

        $columns = [
            'point_histories.id'
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

        $res = PointHistories::datatables($start, $limit, $order, $dir, $search, $filter);

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

    public function syncToLocal (Request $request)
    {
        if (!NetworkHelper::isConnected()) {
            return response()->json([
                'status' => 'offline',
                'message' => 'Tidak ada koneksi, menggunakan data lokal',
                'data' => null
            ]);
        }

        $page = 1;
        $perPage = 500;

        $model = new PointHistories();
        $fillable = array_flip($model->getFillable());

        do {
            $url = config('services.admin_credentials.server_url') . "/api/v1/point_histories?page={$page}&per_page={$perPage}&is_simple=true&order[id]=asc";
            $result = NetworkHelper::curlWithToken($url);
            
            $rows = $result['data'] ?? [];

            if (empty($rows)) break;


            DB::connection('pgsql_companies')->beginTransaction();

            try {
                $ids = collect($rows)->pluck('id')->filter()->toArray();
                $exist_point_history = PointHistories::whereIn('id', $ids)->get()->keyBy('id');
                
                $insert_point_histories = [];

                foreach ($rows as $row) {
                    if (!isset($row['id'])) continue;

                    $data = $exist_point_history[$row['id']] ?? null;
                    $id = $row['id'];

                    $row = array_intersect_key($row, $fillable);

                    if ($data) {
                        unset($row['id']);
                        $data->update($row);

                        if (!empty($row['deleted_at'])) {
                            if (!$data->trashed()) {
                                $data->delete();
                            }
                        } else {
                            if ($data->trashed()) {
                                $data->restore();
                            }
                        }
                    } else {
                        $row['id'] = $id;
                        $insert_point_histories[] = $row;
                    }
                }

                if (!empty($insert_point_histories)) {
                    PointHistories::insert($insert_point_histories);
                }

                DB::connection('pgsql_companies')->commit();
                DB::connection('pgsql_companies')->statement("SELECT SETVAL('point_histories_id_seq', COALESCE((SELECT MAX(id) + 1 FROM point_histories), 1))");

            } catch (\Exception $e) {
                DB::connection('pgsql_companies')->rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal sync poin customer',
                    'error' => $e->getMessage()
                ], 500);
            }

            $page++;

        } while ($page <= ($result['nav']['totalPage'] ?? 1));

        return response()->json([
            'status' => 'success',
            'message' => 'Sync poin customer',
        ]);
    }
}
