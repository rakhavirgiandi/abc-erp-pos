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
        if ($this->runningAsNativeApp()) {
            $this->bootPostgresService();
        }
    }

    protected function bootPostgresService(): void
    {
        /** @var PostgresWindowsService $pgService */
        $pgService = $this->app->make(PostgresWindowsService::class);

        try {
            $pgService->ensureRunning();
            $this->verifyDatabaseConnection();
        } catch (\Throwable $e) {
            Log::critical('[AppServiceProvider] PostgreSQL boot failed: ' . $e->getMessage());

            if (class_exists(\Native\Laravel\Facades\Alert::class)) {
                \Native\Laravel\Facades\Alert::error(
                    'Database Error',
                    "Gagal menjalankan database PostgreSQL:\n\n" . $e->getMessage()
                        . "\n\nCoba jalankan aplikasi sebagai Administrator untuk instalasi pertama."
                );
            }

        }
    }

    protected function verifyDatabaseConnection(): void
    {
        try {
            DB::connection()->getPdo();
            Log::info('[AppServiceProvider] Database connection verified successfully.');
        } catch (\Throwable $e) {
            Log::error('[AppServiceProvider] Database connection failed after pg start: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function runningAsNativeApp(): bool
    {
        return env('NATIVEPHP_RUNNING', false) === true
            || env('NATIVEPHP_RUNNING', '') === 'true'
            || app()->runningInConsole() === false; 
    }
}
