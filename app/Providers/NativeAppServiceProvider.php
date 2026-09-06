<?php

namespace App\Providers;

use App\Services\PostgresWindowsService;
use Native\Desktop\Facades\Window;
use Native\Desktop\Contracts\ProvidesPhpIni;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {   
        config(['services.is_onpremise' => true]);

        if (config('database.connection_mode') == 'service') {
            $pg_service = app(PostgresWindowsService::class);
            $connection_info = $pg_service->getConnectionInfo();
    
            config(['database.connections.pgsql.host' => $connection_info['host']]);
            config(['database.connections.pgsql.port' => $connection_info['port']]);
            config(['database.connections.pgsql.username' => $connection_info['username']]);
            config(['database.connections.pgsql.password' => $connection_info['password']]);
        }

        Window::open()
            ->title(config('app.name'))
            ->width(1280)
            ->height(800)
            ->minWidth(900)
            ->minHeight(600)
            ->url(route('startup'))
            ->resizable(true);
    }

    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [
            'memory_limit'       => '512M',
            'max_execution_time' => '0',
            'max_input_vars' => '500000'
        ];
    }
}
