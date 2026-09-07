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
            CheckForUpdates::class,
            function (CheckForUpdates $event) {

                logger('Manual update check started.');

                AutoUpdater::checkForUpdates();
            }
        );

        /*
        |--------------------------------------------------------------------------
        | NativePHP Auto Updater Events
        |--------------------------------------------------------------------------
        */

        Event::listen(
            CheckingForUpdate::class,
            function () {

                logger('Checking for updates...');
            }
        );

        Event::listen(
            UpdateAvailable::class,
            function ($event) {
                logger('Update available.', ['version' => $event->version ?? null]);
        
                Notification::new()
                    ->title('Update Tersedia')
                    ->message('Versi ' . ($event->version ?? '') . ' siap diunduh.')
                    ->show();
            }
        );

        Event::listen(
            UpdateNotAvailable::class,
            function () {

                logger('No update available. Application is up to date.');
            }
        );

        Event::listen(
            DownloadProgress::class,
            function ($event) {

                logger('Downloading update...', [
                    'percent' => $event->percent ?? 0,
                    'transferred' => $event->transferred ?? 0,
                    'total' => $event->total ?? 0,
                ]);
            }
        );

        Event::listen(
            UpdateDownloaded::class,
            function ($event) {
                logger('Update downloaded successfully.', ['version' => $event->version ?? null]);

                $choice = Alert::new()
                    ->title('Update Siap Dipasang')
                    ->buttons(['Restart Now', 'Nanti'])
                    ->defaultId(0)
                    ->type('info')
                    ->show('Restart sekarang untuk memasang update versi ' . ($event->version ?? '') . '?');


                if ($choice === 0) {
                    AutoUpdater::quitAndInstall();
                }
            }
        );

        Event::listen(
            Error::class,
            function ($event) {
                logger('Auto updater error.', ['error' => $event->error ?? null]);

                Notification::new()
                    ->title('Gagal Memeriksa Update')
                    ->message($event->error ?? 'Terjadi kesalahan saat memeriksa update.')
                    ->show();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Native Application Menu
        |--------------------------------------------------------------------------
        */

        Menu::create(
            Menu::file(),
            Menu::edit(),
            Menu::view(),
            Menu::window()
        );

        /*
        |--------------------------------------------------------------------------
        | Main Application Window
        |--------------------------------------------------------------------------
        */

        Window::open()
            ->title(config('app.name'))
            ->width(1280)
            ->height(800)
            ->minWidth(900)
            ->minHeight(600)
            ->url(route('startup'))
            // ->fullscreen()
            // ->titleBarHidden()
            ->resizable(true);
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
