<?php

namespace App\Http\Middleware;

use App\Events\CheckForUpdates;
use Closure;
use Illuminate\Http\Request;
use Native\Desktop\Facades\Menu;

class NativeMenu
{
    public function handle(Request $request, Closure $next)
    {   
        if (!config('services.is_onpremise')) {
            return $next($request);
        }

        $menu_items = [
            Menu::file(),
            Menu::edit(),
            Menu::view(),
            Menu::window(),
        ];

        if ($request->routeIs('web.login')) {
            $menu_items[] = Menu::make(
                Menu::label('Check for Updates')
                    ->event(CheckForUpdates::class),
            )->label('Help');
        }

        Menu::create(...$menu_items);

        return $next($request);
    }
}