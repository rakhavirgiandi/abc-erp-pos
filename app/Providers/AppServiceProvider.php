<?php

namespace App\Providers;

use App\Services\PostgresWindowsService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Support\LocalSettings;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(\App\Services\PrintBridgeService::class);

        $this->app->singleton(PostgresWindowsService::class, function () {
            return new PostgresWindowsService();
        });

        $this->app->instance(LocalSettings::class, LocalSettings::instance());

        if (config('services.is_onpremise') && config('database.connection_mode') == 'service') {

            $pgService = app(PostgresWindowsService::class);

            $coneection_info = $pgService->getConnectionInfo();

            config([
                'database.connections.pgsql.host' => $coneection_info['host'],
                'database.connections.pgsql.port' => $coneection_info['port'],
                'database.connections.pgsql.username' => $coneection_info['username'],
                'database.connections.pgsql.password' => $coneection_info['password'],
            ]);

            config([
                'database.connections.pgsql_companies.host' => $coneection_info['host'],
                'database.connections.pgsql_companies.port' => $coneection_info['port'],
                'database.connections.pgsql_companies.username' => $coneection_info['username'],
                'database.connections.pgsql_companies.password' => $coneection_info['password'],
            ]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {   
        if (!app()->environment('production')) {
            // config(['services.admin_credentials.server_url' => 'https://abcerp.fanatech.net']);
        }
    }
}
