<?php

namespace App\Jobs;

use App\Helpers\NetworkHelper;
use App\Models\Companies\v1\SalesInvoices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Support\Str;
use App\Services\PostgresWindowsService;

class SyncInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public $tries = 0;

    public $maxExceptions = 3;

    public $backoff = [10, 60, 300];

    public function retryUntil(): \DateTimeInterface
    {
        return now()->addMinutes(30);
    }

    public function __construct(public $id, public $company_db, public $access_token)
    {
        $this->onQueue('sync');
    }

    public function middleware(): array
    {
        return [(new WithoutOverlapping($this->id))->releaseAfter(10)->expireAfter(120)];
    }

    public function handle(): void
    {   
        if (NetworkHelper::isConnected()) {
            try {

                if (config('services.is_onpremise') && config('database.connection_mode') == 'service') {
    
                    $pgService = app(PostgresWindowsService::class);
    
                    $coneection_info = $pgService->getConnectionInfo();
    
                    config([
                        'database.connections.pgsql.host' => $coneection_info['host'],
                        'database.connections.pgsql.port' => localSettings()->get('database.port', $coneection_info['port']),
                        'database.connections.pgsql.username' => localSettings()->get('database.username', $coneection_info['username']),
                        'database.connections.pgsql.password' => localSettings()->get('database.password', $coneection_info['password']),
                    ]);
    
                    config([
                        'database.connections.pgsql_companies.host' => $coneection_info['host'],
                        'database.connections.pgsql_companies.port' => localSettings()->get('database.port', $coneection_info['port']),
                        'database.connections.pgsql_companies.username' => localSettings()->get('database.username', $coneection_info['username']),
                        'database.connections.pgsql_companies.password' => localSettings()->get('database.password', $coneection_info['password']),
                    ]);
                }

                config(['database.connections.pgsql_companies.database' => $this->company_db]);
                DB::purge('pgsql_companies');
    
                $invoice = SalesInvoices::with(['sales_invoice_details', 'point_histories'])
                    ->where('id', $this->id)
                    ->where('is_need_sync', 1)
                    ->where('status', '!=', 'draft')
                    ->first();
    
                if (!$invoice) {
                    $this->log('info', 'Sync invoice dilewati: tidak ditemukan / sudah sync / draft');
                    return;
                }
    
                if (!$invoice->uuid) {
                    $invoice->update([
                        'uuid' => Str::uuid(),
                    ]);
                }
    
                $url = config('services.admin_credentials.server_url') . '/api/v1/sync/sync_sales_invoices';
                $response = NetworkHelper::postWithToken($url, $invoice->toArray(), $this->access_token);
    
                $confirmed = collect($response['data'] ?? [])
                    ->first(fn ($row) => ($row['local_id'] ?? null) == $invoice->id && !empty($row['number']));
    
                if (($response['status'] ?? 'error') !== 'success' || !$confirmed) {
                    throw new \RuntimeException(isset($response['message']) ?  : "Sync invoice {$invoice->ref_number} gagal");
                }
    
                $invoice->update([
                    'number'       => $confirmed['number'],
                    'is_need_sync' => $confirmed['is_need_sync'] ?? 0,
                ]);
    
                $this->log('info', 'Sync invoice berhasil', ['number' => $confirmed['number']]);
            } catch (Throwable $e) {
                // Percobaan gagal tapi masih akan di-retry
                $this->log('error', 'Sync invoice gagal', [
                    'error' => $e->getMessage(),
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);
    
                throw $e;
            }
        }
    }

    // Dipanggil setelah semua percobaan habis
    public function failed(Throwable $e): void
    {
        $this->log('error', $e->getMessage(), [
            'status'    => 500,
            'file'      => $e->getFile(),
            'line'      => $e->getLine(),
            'exception' => $e,
        ]);
    }

    private function log(string $level, string $message, array $extra = []): void
    {
        Log::channel('activity')->{$level}($message, [
            'type'       => 'job',
            'job'        => static::class,
            'invoice_id' => $this->id,
            'company_db' => $this->company_db,
            'attempt'    => $this->attempts(),
        ] + $extra);
    }
}