<?php

namespace App\Providers;

use App\Services\PostgresWindowsService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
