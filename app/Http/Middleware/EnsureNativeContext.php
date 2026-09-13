<?php

// app/Http/Middleware/EnsureNativeContext.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureNativeContext
{
    public function handle(Request $request, Closure $next)
    {   
        if (!config('services.is_onpremise')) {
            abort(404);
        }

        return $next($request);
    }
}