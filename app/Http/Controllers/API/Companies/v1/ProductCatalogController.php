<?php

namespace App\Http\Controllers\API\Companies\v1;

use App\Helpers\NetworkHelper;
use App\Http\Controllers\Controller;
use App\Models\Companies\v1\ProductCatalogs;
use App\Models\Companies\v1\RewardPoints;
use DB;
use Illuminate\Http\Request;

class ProductCatalogController extends Controller
{
    public function get(Request $request, $id = null)
    {
        $params = $request->all();

        if ($id != null) {
            $res = ProductCatalogs::getById($id, $params, $request);
        } else if (isset($params['all']) && $params['all']) {
            $res = ProductCatalogs::getAllResult($params, $request);
        } else {
            $res = ProductCatalogs::getPaginatedResult($params, $request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return ProductCatalogs::createOrUpdate($params, $request->method(), $request);
    }

    public function put(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return ProductCatalogs::createOrUpdate($params, $request->method(), $request);
    }

    public function patch(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return ProductCatalogs::createOrUpdate($params, $request->method(), $request);
    }

    public function delete(Request $request, $id)
    {
        $params = $request->all();

        return ProductCatalogs::deleteById($id, $params, $request);
    }

    public function approve(Request $request, $id)
    {
        $params = $request->all();

        return ProductCatalogs::approveById($id, $params, $request);
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('sanctum')->user();

        $columns = [
            'product_catalogs.id'
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

        $res = ProductCatalogs::datatables($start, $limit, $order, $dir, $search, $filter);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData = $row;
                $nestedData['action'] = '';
                $nestedData['action'] .= '<div class="dropdown">';
                $nestedData['action'] .= '    <button type="button" class="btn btn-primary btn-sm btn-icon" data-bs-toggle="dropdown" aria-expanded="false"><span class="mdi mdi-dots-horizontal fs-5"></span></button>';
                $nestedData['action'] .= '    <div class="dropdown-menu dropdown-menu-animated">';
                $nestedData['action'] .= '        <a class="dropdown-item cursor-pointer" id="edit-data" data-id="'.$row['id'].'">'.__('language.edit').'</a>';
                $nestedData['action'] .= '        <a class="dropdown-item cursor-pointer" id="delete-data" data-id="'.$row['id'].'">'.__('language.delete').'</a>';
                $nestedData['action'] .= '    </div>';
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
            $res = ProductCatalogs::getPaginatedResult($params, $request);

            return response()->json([
                'status' => 'offline',
                'message' => 'Tidak ada koneksi, menggunakan data lokal',
                'data' => $res
            ]);
        }

        $page = 1;
        $perPage = 500;

        $model = new ProductCatalogs();
        $fillable = array_flip($model->getFillable());

        do {
            $url = config('services.admin_credentials.server_url') . "/api/v1/product_catalogs?page={$page}&per_page={$perPage}&is_simple=true&order[id]=asc";
            $result = NetworkHelper::curlWithToken($url);

            $rows = $result['data'] ?? [];

            if (empty($rows)) break;

            DB::connection('pgsql_companies')->beginTransaction();

            try {
                $ids = collect($rows)->pluck('id')->filter()->toArray();
                $product_catalogue = ProductCatalogs::withTrashed()->whereIn('id', $ids)->get()->keyBy('id');

                $new_rows = [];
                foreach ($rows as $row) {
                    if (!isset($row['id'])) continue;
                    $data = $product_catalogue[$row['id']] ?? null;
                    $id = $row['id'];
                    
                    $row = array_intersect_key($row, $fillable);
                    
                    if ($data) {
                        unset($row['id']);
                        $data->update($row); // UPDATE

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
                        $new_rows[] = $row; // INSERT
                    }
                }

                if (!empty($new_rows)) {
                    ProductCatalogs::insert($new_rows);
                }

                DB::connection('pgsql_companies')->commit();
                DB::connection('pgsql_companies')->statement("SELECT SETVAL('product_catalogs_id_seq', COALESCE((SELECT MAX(id) + 1 FROM product_catalogs), 1))");

            } catch (\Exception $e) {
                DB::connection('pgsql_companies')->rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal sync produk katalog',
                    'error' => $e->getMessage()
                ], 500);
            }

            $page++;

        } while ($page <= ($result['nav']['totalPage'] ?? 1));

        return response()->json([
            'status' => 'success',
            'message' => 'Sync produk katalog berhasil',
        ]);
    }
}
