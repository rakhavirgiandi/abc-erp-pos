<?php 

namespace App\Http\Middleware;

use App\Models\GeneralSettings;
use Closure;

class SetupConfig {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        // if (!$user) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Unauthorized',
        //         'detail' => 'Check Setup Config Middleware'
        //     ], 401);
        // }

        if ($user) {
            config(['user' => $user->toArray()]);
        }

        if (env('IS_ONPREMISE', false)) {
            config([
                'default_db_host' => env('DEFAULT_DB_HOST', '127.0.0.1'),
                'default_db_port' => env('DEFAULT_DB_PORT', '5432'),
                'default_db_driver' => env('DEFAULT_DB_DRIVER', 'pgsql'),
                'default_db_user' => env('DEFAULT_DB_USERNAME', 'root'),
                'default_db_password' => env('DEFAULT_DB_PASSWORD', ''),
            ]);
        } else {
            $general_settings = GeneralSettings::get();
    
            foreach ($general_settings as $general_setting) {
                config([$general_setting['key'] => $general_setting['value']]);
            }
        }

        return $next($request);
    }
}

// namespace App\Http\Middleware;

// use App\Helpers\DateHelper;
// use App\Helpers\GlobalHelper;
// use App\Models\Branches;
// use App\Models\Currencies;
// use App\Models\DefaultAccounts;
// use App\Models\GeneralSettings;
// use App\Models\Projects;
// use App\Models\Warehouses;
// use Closure;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Config;
// use Symfony\Component\HttpFoundation\Response;

// class SetupConfig
// {
//     /**
//      * Handle an incoming request.
//      *
//      * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
//      */
//     public function handle(Request $request, Closure $next): Response
//     {
//         $user = $request->user();

//         if (!$user) {
//             return response()->json([
//                 'status' => 'error',
//                 'message' => 'Unauthorized',
//                 'detail' => 'Check Setup Config Middleware'
//             ], 401);
//         }

//         $general_settings = GeneralSettings::get();
//         $default_accounts = DefaultAccounts::get();

//         config(['user' => $user->toArray()]);
//         config([
//             'sync.server_url' => env('SYNC_SERVER_URL', 'https://abcerp.fanatech.net'),
//             'sync.email'      => env('SYNC_EMAIL', 'admin@gmail.com'),
//             'sync.password'   => env('SYNC_PASSWORD', '123'),
//         ]);
        
//         foreach ($general_settings as $general_setting) {
//             if ($general_setting['key'] == 'access_token') {
//                 config(['general_settings.sync_token' => $general_setting['value']]);
//             }

//             config(['general_settings.'.$general_setting['key'] => $general_setting['value']]);
//             if ($general_setting['type'] == 'App\Models\Branches') {
//                 config(['general_settings.branch_name' => 'N/A']);
//                 $branch = Branches::select('id', 'name')->where('id', $general_setting['value'])->first();
//                 if ($branch) {
//                     config(['general_settings.branch_name' => $branch['name']]);
//                 }
//             } else if ($general_setting['type'] == 'App\Models\Warehouses') {
//                 config(['general_settings.warehouse_name' => 'N/A']);
//                 $warehouse = Warehouses::select('id', 'name')->where('id', $general_setting['value'])->first();
//                 if ($warehouse) {
//                     config(['general_settings.warehouse_name' => $warehouse['name']]);
//                 }
//             } else if ($general_setting['type'] == 'App\Models\Projects') {
//                 config(['general_settings.project_name' => 'N/A']);
//                 $project = Projects::select('id', 'name')->where('id', $general_setting['value'])->first();
//                 if ($project) {
//                     config(['general_settings.project_name' => $project['name']]);
//                 }
//             } else if ($general_setting['type'] == 'App\Models\Currencies') {
//                 config(['general_settings.currency_name' => 'N/A']);
//                 $currency = Currencies::select('id', 'name')->where('id', $general_setting['value'])->first();
//                 if ($currency) {
//                     config(['general_settings.currency_name' => $currency['name']]);
//                 }
//             }
//         }

//         foreach ($default_accounts as $default_account) {
//             config(['default_accounts.'.$default_account['key'] => $default_account['value']]);
//         }

//         Config::set('request.method', $request->method());
//         Config::set('request.url', $request->fullUrl());
//         if (config('request.method') == 'GET' || GlobalHelper::findString('datatables', config('request.url'), 'i', '')) {
//             return $next($request);
//         }

//         parse_str($request->getQueryString(), $query_string);
//         Config::set('request.host', $request->getSchemeAndHttpHost());
//         Config::set('request.header', $request->header());
//         Config::set('request.param', $query_string);
//         if (!empty($content = $request->all())) {
//             Config::set('request.body_request', $content);
//         }
//         Config::set('request.ip_address', GlobalHelper::getClientIP());
//         Config::set('request.request_at', DateHelper::getCurrentDate('Y-m-d H:i:s', 'Asia/Jakarta'));
//         $path = explode('/', $request->path());
//         Config::set('request.path', $path);
//         Config::set('request.user.email', $user['email']);
//         $res = $next($request);

//         Config::set('response.response_at', DateHelper::getCurrentDate('Y-m-d H:i:s', 'Asia/Jakarta'));
//         Config::set('response.http_status', $res->getStatusCode());
//         Config::set('response.error_message', '');
//         if (config('request.method') != 'GET') {
//             Config::set('response.body_response', $res->original);
//         }

//         if ($res->getStatusCode() >= 200 && $res->getStatusCode() < 400) {
//             GlobalHelper::pushLog('info',config('request'),config('response'));
//         }

//         return $res;
    
//     }
// }
