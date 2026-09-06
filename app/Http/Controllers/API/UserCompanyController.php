<?php

namespace App\Http\Controllers\API;

use App\Helpers\NetworkHelper;
use App\Http\Controllers\Controller;
use App\Models\Companies;
use App\Models\CompanyCredentials;
use App\Models\Subscriptions;
use App\Models\UserCompanies;
use Cache;
use Illuminate\Http\Request;
use Str;

class UserCompanyController extends Controller
{
    public function get(Request $request, $id = null)
    {
        $params = $request->all();

        if ($id != null) {
            $res = UserCompanies::getById($id, $params, $request);
        } else if (isset($params['all']) && $params['all']) {
            if (config('services.is_onpremise')) {
                $internet_connection = NetworkHelper::isConnected();
                if ($internet_connection) {
                    $url = config('services.admin_credentials.server_url') . "/api/user_companies?all=true";
                    $res_url = NetworkHelper::curlWithToken($url, true);

                    $insert_user_companies = [];
                    $insert_companies = [];
                    $insert_subscriptions = [];
                    $insert_company_credentials = [];


                    if (count($res_url)) {
                        foreach ($res_url as $idx => $item) {
                            $user_company_res = $item;

                            $company = $item['company'];
                            $subscription = $item['subscription'];


                            $find_user_company = UserCompanies::where('id', '=', $item['id'])->first();

                            if ($find_user_company) {
                                unset($user_company_res['created_at']);
                                unset($user_company_res['updated_at']);
                                unset($user_company_res['slug']);
                                unset($user_company_res['company']);
                                unset($user_company_res['subscription']);
                            } else {
                                unset($user_company_res['slug']);
                                unset($user_company_res['company']);
                                unset($user_company_res['subscription']);

                                $insert_user_companies[] = $user_company_res;
                            }

                            if ($company) {
                                $find_company = Companies::where('id', '=', $company['id'])->first();
                                unset($company['accounting_standard']);
                                if ($find_company) {
                                    $find_company->update($company);
                                } else {
                                    $insert_companies[] = $company;
                                }

                                $find_company_credential = CompanyCredentials::where('company_id', '=', $company['id'])->first();

                                if (!$find_company_credential) {
                                    $insert_company_credentials[] = [
                                        'id' => Str::orderedUuid()->toString(),
                                        'company_id' => $company['id'],
                                        'db_driver' => config('database.connections.pgsql.driver'),
                                        'db_host' => config('database.connections.pgsql.host'),
                                        'db_username' => config('database.connections.pgsql.username'),
                                        'db_password' => config('database.connections.pgsql.password'),
                                        'db_database' => $item['slug'],
                                        'db_port' => config('database.connections.pgsql.port')
                                    ];
                                }
                            }

                            if ($subscription) {
                                $find_subscription = Subscriptions::where('id', '=', $subscription['id'])->first();
                                
                                if ($find_subscription) {
                                    $find_subscription->update($subscription);
                                } else {
                                    $insert_subscriptions[] = $subscription;
                                }
                            }
                        }

                        if (count($insert_user_companies) > 0) {
                            UserCompanies::insert($insert_user_companies);
                        }

                        if (count($insert_companies) > 0) {
                            Companies::insert($insert_companies);
                        }

                        if (count($insert_subscriptions) > 0) {
                            Subscriptions::insert($insert_subscriptions);
                        }

                        if (count($insert_company_credentials) > 0) {
                            CompanyCredentials::insert($insert_company_credentials);
                        }
                    }
                }
                $res = UserCompanies::getAllResult($params, $request);

                if (!$internet_connection && $res->original->isEmpty()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Data Perusahaan Anda belum tersinkron dengan Data Perusahaan Server. Pastikan koneksi internet Anda Aktif untuk melakukan proses sinkron',
                        'data' => [
                            'internet_connection' => $internet_connection,
                            'is_onpremise' => config('services.is_onpremise')
                        ]
                    ], 400);
                }
            } else {
                $res = UserCompanies::getAllResult($params, $request);
            }
        } else {
            $res = UserCompanies::getPaginatedResult($params, $request);
        }

        return $res;
    }

    public function post(Request $request)
    {
        $params = $request->all();
        return UserCompanies::createOrUpdate($params, $request->method(), $request);
    }

    public function put(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return UserCompanies::createOrUpdate($params, $request->method());
    }

    public function patch(Request $request, $id)
    {
        $params = $request->all();
        $params['id'] = $id;
        return UserCompanies::createOrUpdate($params, $request->method());
    }

    public function delete(Request $request, $id)
    {
        $params = $request->all();

        return UserCompanies::deleteById($id, $params, $request);
    }

    public function datatables(Request $request)
    {
        $user = auth()->guard('sanctum')->user();

        $columns = [
            0 => 'user_companies.id'
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

        $filter = $request->only(['sDate', 'eDate']);

        $res = UserCompanies::datatables($start, $limit, $order, $dir, $search, $filter);

        $data = [];

        if (!empty($res['data'])) {
            foreach ($res['data'] as $row) {
                $nestedData = $row;
                $nestedData['action'] = '';
                $nestedData['action'] .= '<div class="actions">';
                $nestedData['action'] .= '<a href="#" class="btn btn-icon btn-warning" id="edit-data" data-id="'.$row['id'].'"><i class="fas fa-pencil-alt"></i></a>';
                $nestedData['action'] .= '&nbsp;';
                $nestedData['action'] .= '<a href="#" class="btn btn-icon btn-danger" id="delete-data" data-id="'.$row['id'].'"><i class="fas fa-trash-alt-o"></i></a>';
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
}
