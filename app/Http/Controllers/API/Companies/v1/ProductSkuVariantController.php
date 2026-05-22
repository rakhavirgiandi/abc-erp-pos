<?php

namespace App\Http\Controllers\API\Companies\v1;

use App\Helpers\NetworkHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Companies\v1\ProductSkuVariants;
use Illuminate\Http\Request;

class ProductSkuVariantController extends Controller
{
    public function get(Request $request, $id = null)
    {
        $params = $request->all();

        if ($id != null) {
            $res = ProductSkuVariants::getById($id, $params, $request);
        } else if (isset($params['all']) && $params['all']) {
            $res = ProductSkuVariants::getAllResult($params, $request);
        } else {
            $res = ProductSkuVariants::getPaginatedResult($params, $request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return ProductSkuVariants::createOrUpdate($params, $request->method(), $request);
    }

    public function put(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return ProductSkuVariants::createOrUpdate($params, $request->method(), $request);
    }

    public function patch(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return ProductSkuVariants::createOrUpdate($params, $request->method(), $request);
    }

    public function delete(Request $request, $id)
    {
        $params = $request->all();

        return ProductSkuVariants::deleteById($id, $params, $request);
    }

    public function approve(Request $request, $id)
    {
        $params = $request->all();

        return ProductSkuVariants::approveById($id, $params, $request);
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('sanctum')->user();

        $columns = [
            'product_sku_variants.id'
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

        $res = ProductSkuVariants::datatables($start, $limit, $order, $dir, $search, $filter);

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
            $res = ProductSkuVariants::getPaginatedResult($params, $request);

            return response()->json([
                'status' => 'offline',
                'message' => 'Tidak ada koneksi, menggunakan data lokal',
                'data' => $res
            ]);
        }

        $page = 1;
        $perPage = 500;

        $model = new ProductSkuVariants();
        $fillable = array_flip($model->getFillable());

        do {
            $url = config('services.admin_credentials.server_url') . "/api/v1/product_sku_variants?page={$page}&per_page={$perPage}&is_simple=true&order[id]=asc";
            $result = NetworkHelper::curlWithToken($url);

            $rows = $result['data'] ?? [];

            if (empty($rows)) break;

            DB::connection('pgsql_companies')->beginTransaction();

            try {
                $ids = collect($rows)->pluck('id')->filter()->toArray();
                $exist_sku_variant = ProductSkuVariants::withTrashed()->whereIn('id', $ids)->get()->keyBy('id');

                $insert_sku_variant = [];
                foreach ($rows as $row) {
                    if (!isset($row['id'])) continue;
                    $sku_variant = $exist_sku_variant[$row['id']] ?? null;
                    $id = $row['id'];

                    $row = array_intersect_key($row, $fillable);
                     
                    if ($sku_variant) {
                        unset($row['id']);
                        $sku_variant->update($row); // UPDATE

                        if (!empty($row['deleted_at'])) {
                            if (!$sku_variant->trashed()) {
                                $sku_variant->delete();
                            }
                        } else {
                            if ($sku_variant->trashed()) {
                                $sku_variant->restore();
                            }
                        }
                    } else {
                        $row['id'] = $id;
                        $insert_sku_variant[] = $row; // INSERT
                    }
                }

                if (!empty($insert_sku_variant)) {
                    ProductSkuVariants::insert($insert_sku_variant);
                }

                DB::connection('pgsql_companies')->statement("SELECT SETVAL('product_sku_variants_id_seq', COALESCE((SELECT MAX(id) + 1 FROM product_sku_variants), 1))");
                DB::connection('pgsql_companies')->commit();

            } catch (\Exception $e) {
                DB::connection('pgsql_companies')->rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal sync product sku',
                    'error' => $e->getMessage()
                ], 500);
            }

            $page++;

        } while ($page <= ($result['nav']['totalPage'] ?? 1));

        return response()->json([
            'status' => 'success',
            'message' => 'Sync product sku berhasil',
        ]);
    }
}
