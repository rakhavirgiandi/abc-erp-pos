<?php

use App\Helpers\DateHelper;
use App\Helpers\GlobalHelper;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Config;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.primary' => \App\Http\Middleware\PrimaryAuth::class,
            'setup.config' => \App\Http\Middleware\SetupConfig::class,
            'auth.stock_opname' => \App\Http\Middleware\SOKey::class,
            'companies' => \App\Http\Middleware\Companies::class,
            'api.companies' => \App\Http\Middleware\APICompanies::class,
        ]);

        $middleware->web([
            \App\Http\Middleware\SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Unauthenticated handler
        $exceptions->render(function (AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'data' => null,
                ], 401);
            }
            return redirect()->guest('login');
        });

        // Reportable (logging) handler
        $exceptions->report(function (Throwable $e) {
            if (app()->bound('log')) {
                $request = config('request') ? config('request') : [];

                if (!$request) {
                    $req = request();
                    Config::set('request.app_code', 'ABCERP');
                    Config::set('request.url', $req->fullUrl());
                    parse_str($req->getQueryString(), $query_string);
                    Config::set('request.host', $req->getSchemeAndHttpHost());
                    Config::set('request.method', $req->method());
                    $header = $req->header();
                    if (isset($header['company-id']) && $header['company-id']) {
                        $header = ['company-id' => $header['company-id']];
                    }
                    Config::set('request.header', $header);
                    Config::set('request.param', $query_string);
                    $content = $req->getContent();
                    if (!$content) {
                        $content = $req->all();
                        if ($content) {
                            Config::set('request.body_request', $content);
                        }
                    } else {
                        Config::set('request.body_request', json_decode($content, true));
                    }
                    Config::set('request.ip_address', GlobalHelper::getClientIP());
                    Config::set('request.request_at', DateHelper::getCurrentDate('Y-m-d H:i:s', 'Asia/Jakarta'));
                    $path = explode('/', $req->path());
                    Config::set('request.path', $path);
                    Config::set('request.company_id', '');
                    Config::set('request.user.email', '');
                    $request = config('request');
                }

                if (!isset($request['app_code'])) {
                    $request = array_merge(['app_code' => 'ABCERP'], $request);
                }

                $status_code = (method_exists($e, 'getStatusCode') && $e->getStatusCode() >= 200 && $e->getStatusCode() <= 599) ? $e->getStatusCode() : 500;
                $error_code = (method_exists($e, 'getErrorCode') && $e->getErrorCode() >= 200 && $e->getErrorCode() <= 599) ? $e->getErrorCode() : $status_code;
                $error_message = method_exists($e, 'getMessage') ? preg_replace('/(\t|\r\n|\n)+/', '', $e->getMessage()) : null;

                $err = [
                    'error' => [
                        'code' => $error_code,
                        'message' => $error_message,
                    ],
                ];

                if (isset($e->details[0])) {
                    $detail = [];
                    foreach ($e->details as $d) {
                        $detail = array_merge($detail, $d);
                    }
                    $err['error']['detail'] = $detail;
                    $msg = collect($e->details)->flatten()->first(fn($value) => is_string($value));
                    $err['error']['message'] = (!empty($msg)) ? $msg : $err['error']['message'];
                }

                $response = [
                    'response_at'  => DateHelper::getCurrentDate(),
                    'http_status'  => $error_code,
                    'error_message' => $err,
                ];

                $trace_temp = [];
                if (method_exists($e, 'getFinalTrace')) {
                    $trace_temp = $e->getFinalTrace();
                } elseif (method_exists($e, 'getTrace')) {
                    $trace_temp = $e->getTrace();
                }

                $trace = [];
                foreach ($trace_temp as $i => $t) {
                    $file = $t['file'] ?? '';
                    $line = $t['line'] ?? '';
                    $class = $t['class'] ?? '';
                    $type = $t['type'] ?? '';
                    $function = $t['function'] ?? '';
                    $args = null;
                    if (isset($t['args'])) {
                        foreach ($t['args'] as $j => $a) {
                            $args = ($j == 0) ? gettype($a) : $args . ',' . gettype($a);
                        }
                    }
                    $trace_key = ((int) $i >= 10) ? '#' . $i : '#0' . $i;
                    $trace[$trace_key] = $file . '(' . $line . '): ' . $class . $type . $function . '(' . $args . ')';
                }

                if (isset($request['header']) && $request['header']) {
                    $request['header'] = [
                        'company-id' => $request['header']['company-id'] ?? '',
                        'user-agent' => $request['header']['user-agent'] ?? '',
                    ];
                }

                unset($request['client_id'], $request['client_name']);

                GlobalHelper::pushLog('error', $request, $response, $trace);
            }

            return false;
        });

    })->create();