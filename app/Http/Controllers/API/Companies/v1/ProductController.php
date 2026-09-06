<?php

namespace App\Http\Controllers\API\Companies\v1;

use App\Helpers\NetworkHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Jobs\SyncAllJob;
use App\Models\Companies\v1\ProductCategories;
use App\Models\Companies\v1\Products;
use App\Models\Companies\v1\Taxes;
use App\Models\Companies\v1\Units;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function get(Request $request, $id = null)
    {
        $params = $request->all();

        if ($id != null) {
            $res = Products::getById($id, $params, $request);
        } else if (isset($params['all']) && $params['all']) {
            $res = Products::getAllResult($params, $request);
        } else {
            $res = Products::getPaginatedResult($params, $request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return Products::createOrUpdate($params, $request->method(), $request);
    }

    public function put(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Products::createOrUpdate($params, $request->method(), $request);
    }

    public function patch(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return Products::createOrUpdate($params, $request->method(), $request);
    }

    public function delete(Request $request, $id)
    {
        $params = $request->all();

        return Products::deleteById($id, $params, $request);
    }

    public function approve(Request $request, $id)
    {
        $params = $request->all();

        return Products::approveById($id, $params, $request);
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('sanctum')->user();

        $columns = [
            'products.id'
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

        $res = Products::datatables($start, $limit, $order, $dir, $search, $filter);

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

    public function getStockDatatable(Request $request)
    {
        if (NetworkHelper::isConnected()) {
            $url = config('services.admin_credentials.server_url') . "/api/v1/product_stock_datatables";

            $query = http_build_query([
                'start'  => $request->start,
                'length' => $request->length,
                'search' => $request->search['value'] ?? '',
                'order'  => $request->order,
                'draw'   => $request->draw,
                'warehouse_id'   => $request->warehouse_id ?? null,
                'product_id'   => $request->product_id ?? null,
            ]);

            $fullUrl = $url . '?' . $query;
            $result = NetworkHelper::curlWithToken($fullUrl);

            return response()->json($result);
        }

        $data = Products::stockPerWarehouseDatatable($request);

        return response()->json($data);
    }

    public static function syncToLocal(Request $request)
    {
        if (!NetworkHelper::isConnected()) {
            $params = $request->all();
            $res = Products::getPaginatedResult($params, $request);

            return response()->json([
                'status' => 'offline',
                'message' => 'Tidak ada koneksi, menggunakan data lokal',
                'data' => $res
            ]);
        }

        $page = 1;
        $perPage = 500;

        $model = new Products();
        $fillable = array_flip($model->getFillable());

        do {
            $url = config('services.admin_credentials.server_url') . "/api/v1/products?page={$page}&per_page={$perPage}&is_simple=true&order[id]=asc";
            $result = NetworkHelper::curlWithToken($url);

            $rows = $result['data'] ?? [];

            if (empty($rows)) break;

            DB::connection('pgsql_companies')->beginTransaction();

            try {
                $ids = collect($rows)->pluck('id')->filter()->toArray();
                $exist_product = Products::withTrashed()->whereIn('id', $ids)->get()->keyBy('id');

                $category_map = ProductCategories::pluck('id', 'code'); 
                $tax_map = Taxes::pluck('id', 'code');
                $unit_map = Units::pluck('id', 'code');

                $insert_product = [];

                foreach ($rows as $row) {
                    if (!isset($row['id'])) continue;

                    $product = $exist_product[$row['id']] ?? null;
                    $id = $row['id'];

                    if (!empty($row['category_code'])) {
                        $row['product_category_id'] = $category_map[$row['category_code']] ?? null;
                    }

                    if (!empty($row['unit_code'])) {
                        $row['unit_id'] = $unit_map[$row['unit_code']] ?? null;
                    }

                    if (!empty($row['sale_tax_code'])) {
                        $row['sale_tax_id'] = $tax_map[$row['sale_tax_code']] ?? null;
                    }

                    if (!empty($row['purchase_tax_code'])) {
                        $row['purchase_tax_id'] = $tax_map[$row['purchase_tax_code']] ?? null;
                    }

                    $row = array_intersect_key($row, $fillable);

                    if ($product) {
                        unset($row['id']);
                        $product->update($row);

                        if (!empty($row['deleted_at'])) {
                            if (!$product->trashed()) {
                                $product->delete();
                            }
                        } else {
                            if ($product->trashed()) {
                                $product->restore();
                            }
                        }
                    } else {
                        $row['id'] = $id;
                        $insert_product[] = $row;
                    }
                }

                if (!empty($insert_product)) {
                    Products::insert($insert_product);
                }

                DB::connection('pgsql_companies')->commit();
                DB::connection('pgsql_companies')->statement("SELECT SETVAL('products_id_seq', COALESCE((SELECT MAX(id) + 1 FROM products), 1))");

            } catch (\Exception $e) {
                DB::connection('pgsql_companies')->rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal sync product',
                    'error' => $e->getMessage()
                ], 500);
            }

            $page++;

        } while ($page <= ($result['nav']['totalPage'] ?? 1));

        return response()->json([
            'status' => 'success',
            'message' => 'Sync product berhasil',
        ]);
    }

    public function syncAll(Request $request)
    {
        // AKTIFKAN UNTUK SERVER
        // SyncAllJob::dispatch($request);

        // AKTIFKAN UNTUK LOKAL
        SyncAllJob::dispatchSync($request);

        return response()->json([
            'status' => 'success',
            'message' => 'Sync process started'
        ]);
    }
}
