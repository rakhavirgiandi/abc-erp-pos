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
