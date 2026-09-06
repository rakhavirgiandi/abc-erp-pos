<?php

namespace App\Http\Controllers\API\Companies\v1;

use App\Helpers\NetworkHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Companies\v1\ProductHistories;
use Illuminate\Http\Request;

class ProductHistoryController extends Controller
{
    public function get(Request $request, $id = null)
    {
        $params = $request->all();

        if ($id != null) {
            $res = ProductHistories::getById($id, $params, $request);
        } else if (isset($params['all']) && $params['all']) {
            $res = ProductHistories::getAllResult($params, $request);
        } else {
            $res = ProductHistories::getPaginatedResult($params, $request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return ProductHistories::createOrUpdate($params, $request->method(), $request);
    }

    public function put(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return ProductHistories::createOrUpdate($params, $request->method(), $request);
    }

    public function patch(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return ProductHistories::createOrUpdate($params, $request->method(), $request);
    }

    public function delete(Request $request, $id)
    {
        $params = $request->all();

        return ProductHistories::deleteById($id, $params, $request);
    }

    public function approve(Request $request, $id)
    {
        $params = $request->all();

        return ProductHistories::approveById($id, $params, $request);
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('sanctum')->user();

        $columns = [
            'product_histories.id'
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

        $res = ProductHistories::datatables($start, $limit, $order, $dir, $search, $filter);

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
            $res = ProductHistories::getPaginatedResult($params, $request);

            return response()->json([
                'status' => 'offline',
                'message' => 'Tidak ada koneksi, menggunakan data lokal',
                'data' => $res
            ]);
        }

        // $page = 1;
        // $perPage = 500;

        // $model = new ProductHistories();
        // $fillable = array_flip($model->getFillable());

        // $warehouse_id = config('general_settings.default_warehouse') ?? '';
        // $period = now()->format('Y-m');

        // do {
        //     $url = config('services.admin_credentials.server_url') . "/api/v1/product_histories?page={$page}&per_page={$perPage}&is_simple=true&warehouse_id={$warehouse_id}&periode={$period}";
        //     $result = NetworkHelper::curlWithToken($url);

        //     $rows = $result['data'] ?? [];

        //     if (empty($rows)) break;

        //     DB::connection('pgsql_companies')->beginTransaction();

        //     try {
        //         $ids = collect($rows)->pluck('id')->filter()->toArray();
        //         $exist_history = ProductHistories::whereIn('id', $ids)->get()->keyBy('id');
        //         $id = $row['id'];

        //         $insert_history = [];
        //         foreach ($rows as $row) {
        //             if (!isset($row['id'])) continue;
        //             $history = $exist_history[$row['id']] ?? null;
                    
        //             $row = array_intersect_key($row, $fillable);
                    
        //             if ($history) {
        //                 unset($row['id']);
        //                 $history->update($row); // UPDATE
        //             } else {
        //                 $row['id'] = $id;
        //                 $insert_history[] = $row; // INSERT
        //             }
        //         }

        //         if (!empty($insert_history)) {
        //             ProductHistories::insert($insert_history);
        //         }

        //         DB::connection('pgsql_companies')->statement("SELECT SETVAL('product_histories_id_seq', COALESCE((SELECT MAX(id) + 1 FROM product_histories), 1))");
        //         DB::connection('pgsql_companies')->commit();

        //     } catch (\Exception $e) {
        //         DB::connection('pgsql_companies')->rollBack();
        //         return response()->json([
        //             'status' => 'error',
        //             'message' => 'Gagal sync history',
        //             'error' => $e->getMessage()
        //         ], 500);
        //     }

        //     $page++;

        // } while ($page <= ($result['nav']['totalPage'] ?? 1));

        return response()->json([
            'status' => 'success',
            'message' => 'Sync history berhasil',
        ]);
    }
}
