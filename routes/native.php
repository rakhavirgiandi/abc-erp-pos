<?php

use Illuminate\Support\Facades\Route;
use Native\Desktop\Facades\Window;
use Illuminate\Http\Request;
use Native\Desktop\Facades\AutoUpdater;
use Illuminate\Support\Facades\Cache;

Route::post('window/minimize', fn (Request $request) => Window::minimize($request->input('window_id', 'main')));
Route::post('window/maximize', fn (Request $request) => Window::maximize($request->input('window_id', 'main')));
Route::post('window/restore', fn (Request $request) => Window::unmaximize($request->input('window_id', 'main')));
Route::post('window/close', fn (Request $request) => Window::close($request->input('window_id', 'main')));

Route::post('window/devtools/toggle', function () {
    $window = Window::current();

    if ($window->devToolsOpen()) {
        $window->hideDevTools();
    } else {
        $window->showDevTools();
    }
});

Route::post('updater/check', function () {
    Cache::put('nativephp.updater.status', ['state' => 'checking'], 300);
    AutoUpdater::checkForUpdates();
    return response()->json(['ok' => true]);
});

Route::get('updater/status', function () {
    return response()->json(
        Cache::get('nativephp.updater.status', ['state' => 'idle'])
    );
});

Route::post('updater/download', function () {
    AutoUpdater::downloadUpdate();
    return response()->json(['ok' => true]);
});

Route::post('updater/install', function () {
    AutoUpdater::quitAndInstall();
    return response()->json(['ok' => true]);
});