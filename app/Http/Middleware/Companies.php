<?php

namespace App\Http\Middleware;

use App\Helpers\DateHelper;
use App\Helpers\GlobalHelper;
use App\Models\Companies\v1\Branches;
use App\Models\Companies\v1\Currencies;
use App\Models\Companies\v1\Products;
use App\Models\Companies\v1\Users;
use App\Models\Companies\v1\GeneralSettings;
use App\Models\Companies\v1\DefaultAccounts;
use App\Models\Companies\v1\Projects;
use App\Models\Companies\v1\UserSettings;
use App\Models\Companies\v1\Warehouses;
use App\Models\CompanyCredentials;
use App\Models\Users as ModelsUsers;
use App\Services\PostgresWindowsService;
use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class Companies
{
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

        if (isset($params['company_id']) && $params['company_id'] && isset($params['is_from_mobile']) && $params['is_from_mobile'] == 'true') {
            $company_credential = CompanyCredentials::where('company_id', $params['company_id'])->first();
            config(['database.connections.pgsql_companies' => [
                'driver' => 'pgsql',
                'host' => $company_credential['db_host'],
                'port' => $company_credential['db_port'],
                'database' => $company_credential['db_database'],
                'username' => $company_credential['db_username'],
                'password' => $company_credential['db_password'],
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
                'schema' => 'public',
                'sslmode' => 'prefer',
            ]]);

            if (!config('services.is_onpremise') && config('database.connection_mode') == 'service') {
                $pg_service = app(PostgresWindowsService::class);
                $connection_info = $pg_service->getConnectionInfo();

                config(['database.connections.pgsql_companies.host' => $connection_info['host']]);
                config(['database.connections.pgsql_companies.port' => $connection_info['port']]);
                config(['database.connections.pgsql_companies.username' => $connection_info['username']]);
                config(['database.connections.pgsql_companies.password' => $connection_info['password']]);
            }

            $settings = GeneralSettings::get();
            foreach ($settings->toArray() as $row) {
                config(['general_settings.' . $row['key'] => $row['value']]);
            }

            return $next($request);
        }

        if ($request->session()->get('_login') && $request->session()->get('_company_id') != "") {
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
            ->where('companies.id', $request->session()->get('_company_id'))
            ->leftJoin('companies', 'company_credentials.company_id', '=', 'companies.id')
            ->first();

            // KALO Gini POS Online ERROR, karena ambil root terus, apa yang di buat harus bisa handle Online maupun On Premis
            // config(['database.connections.pgsql_companies' => [
            //     'driver' => env('DEFAULT_DB_DRIVER', 'pgsql'),
            //     'host' => env('DEFAULT_DB_HOST', '127.0.0.1'),
            //     'port' => env('DEFAULT_DB_PORT', '5432'),
            //     'database' => env('DEFAULT_DB_DATABASE', $company['db_database']),
            //     'username' => env('DEFAULT_DB_USERNAME', 'root'),
            //     'password' => env('DEFAULT_DB_PASSWORD', ''),
            //     'charset' => 'utf8',
            //     'prefix' => '',
            //     'prefix_indexes' => true,
            //     'schema' => 'public',
            //     'sslmode' => 'prefer',
            // ]]);

            config(['database.connections.pgsql_companies' => [
                'driver' => 'pgsql',
                'host' => $company['db_host'],
                'port' => $company['db_port'],
                'database' => $company['db_database'],
                'username' => $company['db_username'],
                'password' => $company['db_password'],
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
                'schema' => 'public',
                'sslmode' => 'prefer',
            ]]);

            if (!config('services.is_onpremise') && config('database.connection_mode') == 'service') {
                $pg_service = app(PostgresWindowsService::class);
                $connection_info = $pg_service->getConnectionInfo();

                config(['database.connections.pgsql_companies.host' => $connection_info['host']]);
                config(['database.connections.pgsql_companies.port' => $connection_info['port']]);
                config(['database.connections.pgsql_companies.username' => $connection_info['username']]);
                config(['database.connections.pgsql_companies.password' => $connection_info['password']]);
            }

            $user = Users::select('users.*', 'roles.name as role_name')->where('email', $request->session()->get('_email'))->join('roles', 'roles.id', '=', 'users.role_id')->first();

            if (!$user) {
                return redirect('/choose-company');
            } else if ($user['deleted_at']) {
                return redirect('/choose-company');
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

            foreach ($default_accounts->toArray() as $row) {
                config(['default_accounts.' . $row['key'] => $row['value']]);
            }

            $user_settings = UserSettings::where('user_id', '=', $user['id'])->get();

            foreach ($user_settings->toArray() as $row) {
                config(['local_user_settings.' . $row['key'] => $row['value']]);
            }

            config(['company_id' => $request->session()->get('_company_id')]);

            if ($user) {
                foreach ($user->toArray() as $userKey => $userVal) {
                    config(['user_companies.' . $userKey => $userVal]);
                    if ($userKey == 'branch_id') {
                        config(['user_companies.branch_name' => config('general_settings.branch_name')]);
                        $branch = Branches::select('id', 'name')->where('id', $userVal)->first();
                        if ($branch) {
                            config(['user_companies.branch_name' => $branch->name ?? 'N/A']);
                        }
                    }
                }
                config(['user_companies.details' => $user]);
                config(['user_companies.is_access_to_pos' => $request->session()->get('_is_access_to_pos')]);
                config(['user_companies.is_first' => Branches::count() > 0 ? 0 : 1]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unknown Email on User Companies',
                    'data' => null
                ], 400);
            }

            $skip_route_names = [
                'pos.authorize',
                'pos.logout',
                'pos.print-receipts',
                'pos.settings',
            ];

            $route_name = $request->route()->getName();
            $route_url = $request->path();

            if (!$route_name) {
                GlobalHelper::pushLog('need_debug', 'Unknown Route Name for path /' . $route_url, null);
                dd('no route name for /' . $route_url);
            }

            if (!in_array($route_name, $skip_route_names)) {
                if (!$user->can($route_name)) {
                    abort(403);
                }
            }

            Config::set('request.method', $request->method());
            Config::set('request.url', $request->fullUrl());
            if (config('request.method') == 'GET' || GlobalHelper::findString('datatables', config('request.url'), 'i', '')) {
                DB::disconnect('pgsql_companies');

                return $next($request);
            }

            parse_str($request->getQueryString(), $query_string);
            Config::set('request.host', $request->getSchemeAndHttpHost());
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
            $path = explode('/', $route_url);
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
                GlobalHelper::pushLog('info', config('request'), config('response'));
            }

            DB::disconnect('pgsql_companies');

            return $res;
        } else if ($request->session()->get('_company_id') == "") {
            return redirect('/choose-company');
        }

        return redirect('/login');
    }
}
