<?php

namespace App\Http\Middleware;

use App\Models\Branches;
use App\Models\Currencies;
use App\Models\GeneralSettings;
use App\Models\Projects;
use App\Models\User;
use App\Models\Warehouses;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class PrimaryAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->get('_login')) {
            $general_settings = GeneralSettings::get();

            $user_id = $request->session()->get('_id') ?? null;
            $user = User::where('users.id', '=',  $user_id)
                ->leftJoin('branches', 'users.branch_id', '=', 'branches.id')
                ->select(
                    'users.*',
                    'branches.name as branch_name',
                )
                ->first();

            if (!$user) {
                return redirect('/login');
            }

            config(['user_request' => $user]);
            config(['user' => $user->toArray()]);

            foreach ($general_settings as $general_setting) {
                config(['settings.'.$general_setting['key'] => $general_setting['value']]);
                if ($general_setting['type'] == 'App\Models\Branches') {
                    config(['settings.branch_name' => 'N/A']);
                    $branch = Branches::select('id', 'name')->where('id', $general_setting['value'])->first();
                    if ($branch) {
                        config(['settings.branch_name' => $branch['name']]);
                    }
                } else if ($general_setting['type'] == 'App\Models\Warehouses') {
                    config(['settings.warehouse_name' => 'N/A']);
                    $warehouse = Warehouses::select('id', 'name')->where('id', $general_setting['value'])->first();
                    if ($warehouse) {
                        config(['settings.warehouse_name' => $warehouse['name']]);
                    }
                }  else if ($general_setting['type'] == 'App\Models\Currencies') {
                    config(['settings.currency_name' => 'N/A']);
                    $currency = Currencies::select('id', 'name')->where('id', $general_setting['value'])->first();
                    if ($currency) {
                        config(['settings.currency_name' => $currency['name']]);
                    }
                }
            }
            
            return $next($request);
        }

        return redirect('/');
    }
}
