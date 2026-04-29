<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SOKey
{
    public function handle(Request $request, Closure $next)
    {
        $headerKey = $request->header('X-APP-KEY');

        if ($headerKey !== config('app.so_key')) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        return $next($request);
    }
}