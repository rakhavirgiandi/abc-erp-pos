<?php 

namespace App\Http\Middleware;

use App\Helpers\DateHelper;
use App\Helpers\GlobalHelper;
use App\Http\Models\Purchase\PurchaseOrders;
use App\Models\Companies;
use App\Models\Companies\v1\Branches;
use App\Models\Companies\v1\Currencies;
use App\Models\Companies\v1\Users;
use App\Models\Companies\v1\DefaultAccounts;
use App\Models\Companies\v1\GeneralSettings;
use App\Models\Companies\v1\Projects;
use App\Models\Companies\v1\Warehouses;
use App\Models\CompanyCredentials;
use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class APICompanies {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $params = $request->all();
        $headers = $request->header();

        $token = isset($headers['authorization']) ? $headers['authorization'][0] : null;
        $company_id = isset($headers['company-id']) ? $headers['company-id'][0] : null;
        $user_central = $request->user();
        // $user_central_email = $request->user()->email;
        // $user_central_phone = $request->user()->phone;

        if ($company_id) {
            $company = CompanyCredentials::select([
                'company_credentials.db_host',
                'company_credentials.db_port',
                'company_credentials.db_database',
                'company_credentials.db_username',
                'company_credentials.db_password',
                'companies.id as company_id',
                'companies.user_id',
                'companies.name',
                'companies.address',
                'companies.phone',
                'companies.city',
                'companies.email',
                'companies.tax_id_number',
                'companies.tax_id_address',
                'companies.business_type',
                'companies.main_project_quota',
                'companies.main_lot_quota',
                'companies.is_storefront',
                'companies.domain',
                'companies.subdomain',
                'companies.storefront_project_quota',
            ])
            ->where('companies.id', $company_id)
            ->leftJoin('companies', 'company_credentials.company_id', '=', 'companies.id')
            ->first();

            config(['database.connections.pgsql_companies' => [
                'driver' => env('DEFAULT_DB_DRIVER', 'pgsql'),
                'host' => env('DEFAULT_DB_HOST', '127.0.0.1'),
                'port' => env('DEFAULT_DB_PORT', '5432'),
                'database' => env('DEFAULT_DB_DATABASE', $company['db_database']),
                'username' => env('DEFAULT_DB_USERNAME', 'root'),
                'password' => env('DEFAULT_DB_PASSWORD', ''),
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
                'schema' => 'public',
                'sslmode' => 'prefer',
            ]]);

            $user = Users::select('users.*', 'roles.name as role_name')->where('email', $user_central->email)->join('roles', 'roles.id', '=', 'users.role_id')->first();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email anda tidak terdaftar pada '.$company['name'].'. Harap kontak superadmin perusahaan anda.',
                    'data' => null
                ], 400);
            } else if ($user['deleted_at']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email anda telah dihapus oleh '.$company['name'].'. Harap kontak superadmin perusahaan anda.',
                    'data' => null
                ], 400);
            }

            foreach ($company->toArray() as $k_company => $v_company) {
                config(['companies.'.$k_company => $v_company]);
            }

            $settings = GeneralSettings::get();

            foreach($settings->toArray() as $row) {
                config(['general_settings.'.$row['key'] => $row['value']]);

                if ($row['key'] == 'access_token') {
                    config(['general_settings.sync_token' => $row['value']]);
                } else if ($row['type'] == 'App\Models\Branches') {
                    config(['general_settings.branch_name' => 'N/A']);
                    $branch = Branches::select('id', 'name')->where('id', $row['value'])->first();
                    if ($branch) {
                        config(['general_settings.branch_name' => $branch['name']]);
                    }
                } else if ($row['type'] == 'App\Models\Warehouses') {
                    config(['general_settings.warehouse_name' => 'N/A']);
                    $warehouse = Warehouses::select('id', 'name')->where('id', $row['value'])->first();
                    if ($warehouse) {
                        config(['general_settings.warehouse_name' => $warehouse['name']]);
                    }
                } else if ($row['type'] == 'App\Models\Projects') {
                    config(['general_settings.project_name' => 'N/A']);
                    $project = Projects::select('id', 'name')->where('id', $row['value'])->first();
                    if ($project) {
                        config(['general_settings.project_name' => $project['name']]);
                    }
                } else if ($row['type'] == 'App\Models\Currencies') {
                    config(['general_settings.currency_name' => 'N/A']);
                    $currency = Currencies::select('id', 'name')->where('id', $row['value'])->first();
                    if ($currency) {
                        config(['general_settings.currency_name' => $currency['name']]);
                    }
                }
            }

            $default_accounts = DefaultAccounts::get();

            foreach($default_accounts->toArray() as $row) {
                config(['default_accounts.'.$row['key'] => $row['value']]);
            }

            config(['company_id' => $company_id]);

            if ($user) {
                foreach($user->toArray() as $userKey => $userVal) {
                    config(['user_companies.'.$userKey => $userVal]);
                }
                config(['user_companies.details' => $user]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unknown Email on User Companies',
                    'data' => null
                ], 400);
            }

            Config::set('request.url', $request->fullUrl());

            if (GlobalHelper::findString('datatables', config('request.url'), 'i', '')) {
                DB::disconnect('pgsql_companies');

                return $next($request);
            }

            parse_str($request->getQueryString(), $query_string);
            Config::set('request.host', $request->getSchemeAndHttpHost());
            Config::set('request.method', $request->method());
            Config::set('request.header', $request->header());
            Config::set('request.param', $query_string);
            $content = $request->getContent();
            if (!$content) {
                $content = $request->all();
                if ($content) {
                    Config::set('request.body_request', $content);
                }
            } else {
                Config::set('request.body_request', json_decode($content, true));
            }
            Config::set('request.ip_address', GlobalHelper::getClientIP());
            Config::set('request.request_at', DateHelper::getCurrentDate('Y-m-d H:i:s', 'Asia/Jakarta'));
            $path = explode('/', $request->path());
            Config::set('request.path', $path);
            Config::set('request.slug', $company['db_database']);
            Config::set('request.user.email', $user['email']);

            $res = $next($request);

            Config::set('response.response_at', DateHelper::getCurrentDate('Y-m-d H:i:s', 'Asia/Jakarta'));
            Config::set('response.http_status', $res->getStatusCode());
            Config::set('response.error_message', '');
            if (config('request.method') != 'GET') {
                Config::set('response.body_response', $res->original);
            }

            if ($res->getStatusCode() >= 200 && $res->getStatusCode() < 400) {
                GlobalHelper::pushLog('info',config('request'),config('response'));
            }

            DB::disconnect('pgsql_companies');

            return $res;
        } else if ($company_id == "") {
            return response()->json([
                'status' => 'error',
                'message' => 'Silahkan Pilih Perusahaan',
                'data' => null
            ], 400);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Silahkan logout lalu login kembali',
            'data' => null
        ], 400);
    }
}