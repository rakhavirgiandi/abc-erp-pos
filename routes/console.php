<?php

use App\Jobs\SyncInvoice;
use App\Models\Companies\v1\SalesInvoices;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::call(function () {
    $ctx = Cache::store('file')->get('auth_context');

    if (!$ctx || empty($ctx['company_db']) || empty($ctx['access_token'])) {
        Log::channel('activity')->warning('Sync invoice dilewati: auth_context tidak ada di cache', [
            'type' => 'schedule',
        ]);
        return;
    }

    config(['database.connections.pgsql_companies.database' => $ctx['company_db']]);
    DB::purge('pgsql_companies');

    SalesInvoices::where('is_need_sync', 1)
        ->where('status', '!=', 'draft')
        ->pluck('id')
        ->each(fn ($id) => SyncInvoice::dispatch($id, $ctx['company_db'], $ctx['access_token']));
})->everyTenMinutes()->name('sync-invoices')->withoutOverlapping()->onOneServer();