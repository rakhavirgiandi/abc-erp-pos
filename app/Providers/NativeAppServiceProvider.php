<?php

namespace App\Providers;

use App\Events\CheckForUpdates;
use App\Services\PostgresWindowsService;
use Illuminate\Support\Facades\Event;

use Native\Desktop\Contracts\ProvidesPhpIni;
use Native\Desktop\Facades\AutoUpdater;
use Native\Desktop\Facades\Menu;
use Native\Desktop\Facades\Window;

use Native\Desktop\Events\AutoUpdater\CheckingForUpdate;
use Native\Desktop\Events\AutoUpdater\UpdateAvailable;
use Native\Desktop\Events\AutoUpdater\UpdateNotAvailable;
use Native\Desktop\Events\AutoUpdater\DownloadProgress;
use Native\Desktop\Events\AutoUpdater\UpdateDownloaded;
use Native\Desktop\Events\AutoUpdater\Error;
use Native\Desktop\Facades\Notification;
use Native\Desktop\Facades\Alert;
use Illuminate\Support\Facades\Cache;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        config([
            'services.is_onpremise' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | PostgreSQL Configuration
        |--------------------------------------------------------------------------
        */

        if (config('database.connection_mode') === 'service') {

            $pgService = app(PostgresWindowsService::class);

            $connectionInfo = $pgService->getConnectionInfo();

            config([
                'database.connections.pgsql.host' => $connectionInfo['host'],
                'database.connections.pgsql.port' => $connectionInfo['port'],
                'database.connections.pgsql.username' => $connectionInfo['username'],
                'database.connections.pgsql.password' => $connectionInfo['password'],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check For Updates Menu Event
        |--------------------------------------------------------------------------
        */

        Event::listen(
            CheckingForUpdate::class,
            function () {
                logger('Checking for updates...');
                Cache::put('nativephp.updater.status', ['state' => 'checking'], 300);
            }
        );
        
        Event::listen(
            UpdateAvailable::class,
            function ($event) {
                logger('Update available.', ['version' => $event->version ?? null]);
        
                Cache::put('nativephp.updater.status', [
                    'state' => 'available',
                    'version' => $event->version,
                    'releaseNotes' => is_array($event->releaseNotes)
                        ? implode("\n", $event->releaseNotes)
                        : $event->releaseNotes,
                    'releaseDate' => $event->releaseDate,
                ], 300);
            }
        );
        
        Event::listen(
            UpdateNotAvailable::class,
            function ($event) {
                logger('No update available. Application is up to date.');
                Cache::put('nativephp.updater.status', [
                    'state' => 'not-available',
                    'version' => $event->version ?? null,
                ], 300);
            }
        );
        
        Event::listen(
            DownloadProgress::class,
            function ($event) {
                logger('Downloading update...', ['percent' => $event->percent ?? 0]);
                Cache::put('nativephp.updater.status', [
                    'state' => 'downloading',
                    'percent' => round($event->percent ?? 0),
                ], 300);
            }
        );
        
        Event::listen(
            UpdateDownloaded::class,
            function ($event) {
                logger('Update downloaded successfully.', ['version' => $event->version ?? null]);
                Cache::put('nativephp.updater.status', [
                    'state' => 'downloaded',
                    'version' => $event->version ?? null,
                ], 300);
            }
        );
        
        Event::listen(
            Error::class,
            function ($event) {
                logger('Auto updater error.', ['error' => $event->error ?? null]);
                Cache::put('nativephp.updater.status', [
                    'state' => 'error',
                    'message' => $event->error ?? 'Terjadi kesalahan saat memeriksa update.',
                ], 300);
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Native Application Menu
        |--------------------------------------------------------------------------
        */

        /*
        |--------------------------------------------------------------------------
        | Main Application Window
        |--------------------------------------------------------------------------
        */

        $window = Window::open()
            ->title(config('app.name'))
            ->width(1280)
            ->height(800)
            ->minWidth(900)
            ->minHeight(600)
            // ->fullscreen()
            ->titleBarHidden()
            ->resizable(true);

        if (app()->isProduction()) {
            $window->url(route('startup'));
        }
    }

    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [
            'memory_limit' => '512M',
            'max_execution_time' => '0',
            'max_input_vars' => '500000',
        ];
    }
}
