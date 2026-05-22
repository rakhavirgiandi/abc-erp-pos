<?php

namespace App\Http\Controllers\API\Companies\v1;

use App\Helpers\NetworkHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Companies\v1\Products;
use App\Models\Companies\v1\ProductVariants;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function get(Request $request, $id = null)
    {
        $params = $request->all();

        if ($id != null) {
            $res = ProductVariants::getById($id, $params, $request);
        } else if (isset($params['all']) && $params['all']) {
            $res = ProductVariants::getAllResult($params, $request);
        } else {
            $res = ProductVariants::getPaginatedResult($params, $request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return ProductVariants::createOrUpdate($params, $request->method(), $request);
    }

    public function put(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return ProductVariants::createOrUpdate($params, $request->method(), $request);
    }

    public function patch(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return ProductVariants::createOrUpdate($params, $request->method(), $request);
    }

    public function delete(Request $request, $id)
    {
        $params = $request->all();

        return ProductVariants::deleteById($id, $params, $request);
    }

    public function approve(Request $request, $id)
    {
        $params = $request->all();

        return ProductVariants::approveById($id, $params, $request);
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('sanctum')->user();

        $columns = [
            'product_variants.id'
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

        $res = ProductVariants::datatables($start, $limit, $order, $dir, $search, $filter);

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
            $res = ProductVariants::getPaginatedResult($params, $request);

            return response()->json([
                'status' => 'offline',
                'message' => 'Tidak ada koneksi, menggunakan data lokal',
                'data' => $res
            ]);
        }

        $page = 1;
        $perPage = 500;

        $model = new ProductVariants();
        $fillable = array_flip($model->getFillable());

        $allProductIds = [];
        $totalPage = 1;

        do {
            $url = config('services.admin_credentials.server_url') . "/api/v1/product_variants?page={$page}&per_page={$perPage}&is_simple=true";
            $result = NetworkHelper::curlWithToken($url);

            $rows = $result['data'] ?? [];
            if (empty($rows)) break;

            $ids = collect($rows)->pluck('product_id')->filter()->unique()->toArray();
            $allProductIds = array_unique(array_merge($allProductIds, $ids));

            $totalPage = $result['nav']['totalPage'] ?? 1;
            $page++;

        } while ($page <= $totalPage);

        if (empty($allProductIds)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Tidak ada data untuk di-sync',
            ]);
        }

        $products = Products::withTrashed()->whereIn('id', $allProductIds)->get()->keyBy('id');
        $product_ids = $products->pluck('id')->toArray();

        if (!empty($product_ids)) {
            ProductVariants::withTrashed()->whereIn('product_id', $product_ids)->forceDelete();
        }

        $page = 1;

        do {
            $url = config('services.admin_credentials.server_url') . "/api/v1/product_variants?page={$page}&per_page={$perPage}&is_simple=true";
            $result = NetworkHelper::curlWithToken($url);

            $rows = $result['data'] ?? [];
            if (empty($rows)) break;

            DB::connection('pgsql_companies')->beginTransaction();

            try {
                $insert_product_variant = [];

                foreach ($rows as $row) {
                    if (!isset($row['product_id'])) continue;

                    $product = $products[$row['product_id']] ?? null;
                    if (!$product) continue;

                    $row['product_id'] = $product->id;
                    $row = array_intersect_key($row, $fillable);
                    $insert_product_variant[] = $row;
                }

                if (!empty($insert_product_variant)) {
                    ProductVariants::insert($insert_product_variant);
                }

                DB::connection('pgsql_companies')->commit();

            } catch (\Exception $e) {
                DB::connection('pgsql_companies')->rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal sync produk variant',
                    'error' => $e->getMessage()
                ], 500);
            }

            $page++;

        } while ($page <= ($result['nav']['totalPage'] ?? 1));

        DB::connection('pgsql_companies')->statement("SELECT SETVAL('product_variants_id_seq', COALESCE((SELECT MAX(id) + 1 FROM product_variants), 1))");

        return response()->json([
            'status' => 'success',
            'message' => 'Sync produk variant berhasil',
        ]);
    }
}
