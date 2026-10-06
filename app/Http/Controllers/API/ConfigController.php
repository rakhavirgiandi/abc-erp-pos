<?php

namespace App\Http\Controllers\API;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Native\Desktop\Facades\Settings;
use Illuminate\Support\Arr;

class ConfigController extends Controller
{   
    private const REQUIRED_KEY = [
        "database.port",
        "database.database",
        "database.username",
        "database.password"
    ];
    
    public function post(Request $request)
    {
        $params = $request->all();

        $flattened = Arr::dot($params);
        
        foreach ($flattened as $key => $value) {
            if (in_array($key, self::REQUIRED_KEY) && (!$value || $value === '')) {
                continue;
            }

            localSettings()->set($key, $value);
        }

        Artisan::call('optimize:clear');

        return response()->json([
            'status' => 'success',
            'message' => 'Konfigurasi berhasil diperbaharui'
        ]);
    }

    public function checkDBConnection (Request $request)
    {   
        $params = $request->all();

        try {
            Config::set('database.connections.test', [
                'driver' => 'pgsql',
                'host' => '127.0.0.1',
                'port' => $params['database']['port'],
                'database' => $params['database']['database'],
                'username' => $params['database']['username'],
                'password' => $params['database']['password'],
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
                'schema' => 'public',
                'sslmode' => 'prefer',
            ]);

            DB::purge('test');
    
            $connection = DB::connection('test');

            $connection->getPdo();

            $connection->select('SELECT 1');

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil koneksi ke database'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        } finally {
            DB::disconnect('test');
        }
    }
}
