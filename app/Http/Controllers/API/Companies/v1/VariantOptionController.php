<?php

namespace App\Http\Controllers\API\Companies\v1;

use App\Helpers\NetworkHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Companies\v1\VariantOptions;
use Illuminate\Http\Request;

class VariantOptionController extends Controller
{
    public function get(Request $request, $id = null)
    {
        $params = $request->all();

        if ($id != null) {
            $res = VariantOptions::getById($id, $params, $request);
        } else if (isset($params['all']) && $params['all']) {
            $res = VariantOptions::getAllResult($params, $request);
        } else {
            $res = VariantOptions::getPaginatedResult($params, $request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return VariantOptions::createOrUpdate($params, $request->method(), $request);
    }

    public function put(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return VariantOptions::createOrUpdate($params, $request->method(), $request);
    }

    public function patch(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return VariantOptions::createOrUpdate($params, $request->method(), $request);
    }

    public function delete(Request $request, $id)
    {
        $params = $request->all();

        return VariantOptions::deleteById($id, $params, $request);
    }

    public function approve(Request $request, $id)
    {
        $params = $request->all();

        return VariantOptions::approveById($id, $params, $request);
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('sanctum')->user();

        $columns = [
            'variant_options.id'
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

        $res = VariantOptions::datatables($start, $limit, $order, $dir, $search, $filter);

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
            $res = VariantOptions::getPaginatedResult($params, $request);

            return response()->json([
                'status' => 'offline',
                'message' => 'Tidak ada koneksi, menggunakan data lokal',
                'data' => $res
            ]);
        }

        $page = 1;
        $perPage = 500;

        $model = new VariantOptions();
        $fillable = array_flip($model->getFillable());

        do {
            $url = config('services.admin_credentials.server_url') . "/api/v1/variant_options?page={$page}&per_page={$perPage}&is_simple=true&order[id]=asc";
            $result = NetworkHelper::curlWithToken($url);

            $rows = $result['data'] ?? [];

            if (empty($rows)) break;

            DB::connection('pgsql_companies')->beginTransaction();

            try {
                $ids = collect($rows)->pluck('id')->filter()->toArray();
                $exist_variant = VariantOptions::withTrashed()->whereIn('id', $ids)->get()->keyBy('id');

                $insert_variant = [];
                foreach ($rows as $row) {
                    if (!isset($row['id'])) continue;
                    $variant = $exist_variant[$row['id']] ?? null;
                    $id = $row['id'];

                    $row = array_intersect_key($row, $fillable);

                    if ($variant) {
                        unset($row['id']);
                        $variant->update($row); // UPDATE

                        if (!empty($row['deleted_at'])) {
                            if (!$variant->trashed()) {
                                $variant->delete();
                            }
                        } else {
                            if ($variant->trashed()) {
                                $variant->restore();
                            }
                        }
                    } else {
                        $row['id'] = $id;
                        $insert_variant[] = $row; // INSERT
                    }
                }

                if (!empty($insert_variant)) {
                    VariantOptions::insert($insert_variant);
                }

                DB::connection('pgsql_companies')->commit();
                DB::connection('pgsql_companies')->statement("SELECT SETVAL('variant_options_id_seq', COALESCE((SELECT MAX(id) + 1 FROM variant_options), 1))");

            } catch (\Exception $e) {
                DB::connection('pgsql_companies')->rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal sync variant',
                    'error' => $e->getMessage()
                ], 500);
            }

            $page++;

        } while ($page <= ($result['nav']['totalPage'] ?? 1));

        return response()->json([
            'status' => 'success',
            'message' => 'Sync variant berhasil',
        ]);
    }
}
