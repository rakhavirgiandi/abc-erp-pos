<?php
namespace App\Http\Controllers\API;

use App\Services\PostgresWindowsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class StartupController extends Controller
{
    public function status(Request $request, PostgresWindowsService $pgService): JsonResponse
    {
        $cacheKey = 'startup_pg_status';

        try {
            if (Cache::has($cacheKey . '_ready')) {
                return response()->json([
                    'status'       => 'ready',
                    'step'         => 'Database siap.',
                    'port'         => Cache::get($cacheKey . '_port', config('database.connections.pgsql.port')),
                    'port_changed' => false,
                    'redirect'     => route('web.login'),
                ]);
            }

            if (Cache::has($cacheKey . '_running')) {
                return response()->json([
                    'status' => 'starting',
                    'step'   => 'Sedang memulai database...',
                ]);
            }

            Cache::put($cacheKey . '_running', true, 60);

            $result = $pgService->ensureRunning();

            // Jalankan post-install commands (migrate, passport, dll)
            // hanya sekali saat production, setelah DB ready
            $this->runPostInstallIfNeeded();

            Cache::put($cacheKey . '_port', $result['port'], 3600);
            Cache::put($cacheKey . '_ready', true, 3600);
            Cache::forget($cacheKey . '_running');

            return response()->json([
                'status'       => 'ready',
                'step'         => 'Database siap!',
                'port'         => $result['port'],
                'port_changed' => $result['port_changed'],
                'old_port'     => $result['old_port'] ?? null,
                'message'      => $result['message'],
                'redirect'     => route('web.login'),
            ]);

        } catch (\Throwable $e) {
            Cache::forget($cacheKey . '_running');
            Log::error('[StartupController] PostgreSQL startup failed: ' . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'step'    => 'Gagal menghubungkan ke database.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function retry(PostgresWindowsService $pgService): JsonResponse
    {
        Cache::forget('startup_pg_status_ready');
        Cache::forget('startup_pg_status_running');
        Cache::forget('startup_pg_status_port');

        return $this->status(request(), $pgService);
    }

    /**
     * Jalankan post-install commands hanya sekali saat production.
     * Ditandai dengan flag file agar tidak jalan berulang.
     */
    protected function runPostInstallIfNeeded(): void
    {
        if (!app()->isProduction()) {
            Log::info('[Startup] Development mode — skip post-install commands.');
            return;
        }

        $flagFile = storage_path('app' . DIRECTORY_SEPARATOR . '.post_install_done');

        if (file_exists($flagFile)) {
            Log::info('[Startup] Post-install sudah pernah dijalankan, skip.');
            return;
        }

        Log::info('[Startup] Menjalankan post-install commands...');

        $flagDir = dirname($flagFile);
        if (! is_dir($flagDir)) {
            mkdir($flagDir, 0755, true);
        }

        $commands = [
            ['migrate', ['--force' => true]],
            ['passport:client', [
                '--personal' => true,
                '--name'     => 'ABC POS Personal Access Client',
                '--no-interaction' => true,
            ]],
            ['passport:keys', ['--force' => true]],
        ];

        $allSuccess = true;

        foreach ($commands as [$command, $options]) {
            Log::info("[Startup] Running: php artisan {$command}");
            try {
                $exitCode = Artisan::call($command, $options);
                Log::info("[Startup] Output: " . Artisan::output());

                if ($exitCode !== 0) {
                    $allSuccess = false;
                    Log::error("[Startup] Command '{$command}' exited with code {$exitCode}");
                }
            } catch (\Throwable $e) {
                $allSuccess = false;
                Log::error("[Startup] Command '{$command}' failed: " . $e->getMessage());
            }
        }

        if ($allSuccess) {
            file_put_contents($flagFile, date('Y-m-d H:i:s'));
            Log::info('[Startup] Post-install selesai: ' . date('Y-m-d H:i:s'));
        } else {
            Log::warning('[Startup] Post-install ada yang gagal — akan dicoba lagi di startup berikutnya.');
        }
    }
}