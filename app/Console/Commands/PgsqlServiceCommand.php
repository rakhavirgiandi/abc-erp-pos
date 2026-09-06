<?php

namespace App\Console\Commands;

use App\Services\PostgresWindowsService;
use Illuminate\Console\Command;

class PgsqlServiceCommand extends Command
{
    protected $signature = 'pgsql:service
                            {action : Aksi yang dijalankan: status|start|stop|restart|install|uninstall|init}';

    protected $description = 'Kelola PostgreSQL Windows Service yang di-bundle dalam app';

    public function __construct(protected PostgresWindowsService $pg)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $action = $this->argument('action');

        return match ($action) {
            'status'    => $this->actionStatus(),
            'start'     => $this->actionStart(),
            'stop'      => $this->actionStop(),
            'restart'   => $this->actionRestart(),
            'install'   => $this->actionInstall(),
            'uninstall' => $this->actionUninstall(),
            default     => $this->error("Action tidak dikenal: {$action}") ?? self::FAILURE,
        };
    }

    protected function actionStatus(): int
    {
        $registered = $this->pg->isServiceRegistered();
        $running    = $this->pg->isServiceRunning();

        $this->table(
            ['Property', 'Value'],
            [
                ['Service Name', config('services.pgsql.service_name')],
                ['Registered',   $registered ? '<info>YES</info>' : '<comment>NO</comment>'],
                ['Running',      $running    ? '<info>YES</info>' : '<comment>NO</comment>'],
                ['Port',         config('services.pgsql.port')],
                ['Data Path',    config('services.pgsql.data_path')],
                ['Bin Path',     config('services.pgsql.bin_path')],
            ]
        );

        return self::SUCCESS;
    }

    protected function actionStart(): int
    {
        $this->info('Starting PostgreSQL service...');
        try {
            $this->pg->ensureRunning();
            $this->info('✅ PostgreSQL service is running.');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('❌ Failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    protected function actionStop(): int
    {
        $this->info('Stopping PostgreSQL service...');
        $name = config('services.pgsql.service_name');
        exec("sc stop {$name} 2>&1", $out, $code);
        if ($code === 0) {
            $this->info('Service stopped.');
            return self::SUCCESS;
        }
        $this->error('Failed: ' . implode("\n", $out));
        return self::FAILURE;
    }

    protected function actionRestart(): int
    {
        $this->actionStop();
        sleep(2);
        return $this->actionStart();
    }

    protected function actionInstall(): int
    {
        $this->info('Installing PostgreSQL Windows Service...');
        $this->warn('PERHATIAN: Ini memerlukan hak Administrator!');

        if (! $this->confirm('Lanjutkan?', true)) {
            return self::SUCCESS;
        }

        try {
            $this->pg->ensureRunning();
            $this->info('Service installed and running.');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }
    }

    protected function actionUninstall(): int
    {
        $this->warn('Ini akan menghentikan dan menghapus Windows Service PostgreSQL.');
        $this->warn('DATA di storage/pgsql/data TIDAK akan dihapus.');

        if (! $this->confirm('Lanjutkan uninstall service?')) {
            return self::SUCCESS;
        }

        try {
            $this->pg->removeService();
            $this->info('Service removed.');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }
    }
}
