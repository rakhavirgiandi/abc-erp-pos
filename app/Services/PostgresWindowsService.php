<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use RuntimeException;

class PostgresWindowsService
{
    /**
     * Nama Windows Service yang akan didaftarkan.
     */
    protected string $serviceName;

    /**
     * Path ke folder PostgreSQL binaries (bundled dalam project).
     * Contoh: C:\MyApp\pgsql
     */
    protected string $pgBinPath;

    /**
     * Path ke data directory PostgreSQL (runtime, di storage/).
     * Contoh: C:\MyApp\storage\pgsql\data
     */
    protected string $pgDataPath;

    /**
     * Port PostgreSQL.
     */
    protected int $pgPort;

    /**
     * Username superuser PostgreSQL awal.
     */
    protected string $pgUser;

    /**
     * Password superuser PostgreSQL awal.
     */
    protected string $pgPassword;

    /**
     * Nama database default yang akan dibuat.
     */
    protected string $pgDatabase;

    public function __construct()
    {
        $this->serviceName = config('services.pgsql.service_name', 'ABC POS');
        $this->pgBinPath   = str_replace('/', DIRECTORY_SEPARATOR, config('services.pgsql.bin_path', base_path('pgsql/bin')));
        $this->pgDataPath  = str_replace('/', DIRECTORY_SEPARATOR, config('services.pgsql.data_path', storage_path('pgsql/data')));
        $this->pgPort      = (int) config('services.pgsql.port', 5432);
        $this->pgUser      = config('services.pgsql.superuser', 'bukanadmin');
        $this->pgPassword  = config('services.pgsql.password', 'B15mi1Ll@h');
        $this->pgDatabase  = config('services.pgsql.database', 'abc_erp_db');
    }

    // -------------------------------------------------------------------------
    // Public API
    // -------------------------------------------------------------------------

    /**
     * Entry point utama: dipanggil dari AppServiceProvider saat boot.
     * Menginisialisasi data dir jika belum ada, mendaftarkan service jika belum,
     * dan memastikan service sedang berjalan.
     */
    public function ensureRunning(): void
    {
        if (! $this->isWindows()) {
            // Di luar Windows (dev Mac/Linux), skip — pakai PostgreSQL sistem
            Log::info('[PgService] Non-Windows environment detected, skipping Windows service setup.');
            return;
        }

        try {
            $this->initDataDirectoryIfNeeded();
            $this->registerServiceIfNeeded();
            $this->startServiceIfNotRunning();
        } catch (\Throwable $e) {
            Log::error('[PgService] Failed to ensure PostgreSQL is running: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Hentikan dan hapus Windows Service (untuk uninstall app).
     */
    public function removeService(): void
    {
        $name = $this->serviceName;
    
        if ($this->isServiceRunning()) {
            $this->runCommand("sc stop \"{$name}\"");
            sleep(3);
        }
    
        if ($this->isServiceRegistered()) {
            $this->runCommand("sc delete \"{$name}\"");
            Log::info("[PgService] Service '{$name}' removed.");
        }
    }

    /**
     * Cek apakah service sedang berjalan.
     */
    public function isServiceRunning(): bool
    {
        $name   = $this->serviceName;
        $output = shell_exec("sc query \"{$name}\" 2>&1");
        return $output !== null && str_contains($output, 'RUNNING');
    }

    /**
     * Cek apakah service sudah terdaftar di Windows.
     */
    public function isServiceRegistered(): bool
    {
        $name   = $this->serviceName;
        $output = shell_exec("sc query \"{$name}\" 2>&1");
        return $output !== null && ! str_contains($output, 'FAILED 1060');
    }

    // -------------------------------------------------------------------------
    // Internal Steps
    // -------------------------------------------------------------------------

    /**
     * Inisialisasi PostgreSQL data directory menggunakan initdb jika belum ada.
     */
    protected function initDataDirectoryIfNeeded(): void
    {
        $pgVersionFile = $this->pgDataPath . DIRECTORY_SEPARATOR . 'PG_VERSION';

        if (file_exists($pgVersionFile)) {
            Log::info('[PgService] Data directory already initialized.');
            return;
        }

        // Hapus folder jika ada (termasuk sisa run sebelumnya)
        if (is_dir($this->pgDataPath)) {
            Log::info('[PgService] Removing stale data directory...');
            exec('cmd /c rd /s /q "' . $this->pgDataPath . '"');
            sleep(1);
        }

        Log::info('[PgService] Initializing PostgreSQL data directory...');

        // Buat folder parent saja (storage/pgsql/), 
        // biarkan initdb yang buat folder data/ sendiri
        $parentDir = dirname($this->pgDataPath);
        if (! is_dir($parentDir)) {
            mkdir($parentDir, 0755, true);
        }

        // Tulis pwfile di parent dir, BUKAN di data dir
        $pwFile = $parentDir . DIRECTORY_SEPARATOR . 'pwfile.tmp';
        file_put_contents($pwFile, $this->pgPassword);

        $initdb = $this->bin('initdb');
        $output = [];
        $cmd = "\"{$initdb}\" "
            . "-D \"{$this->pgDataPath}\" "
            . "-U \"{$this->pgUser}\" "
            . "--pwfile=\"{$pwFile}\" "
            . "--encoding=UTF8 "
            . "--locale=C "
            . "-A md5";

        $result = $this->runCommand($cmd, $output);

        @unlink($pwFile);  // hapus pwfile dari parent dir

        if ($result !== 0) {
            throw new RuntimeException("initdb failed:\n" . implode("\n", $output));
        }

        $this->patchPostgresConf();
        $this->createAppDatabase();

        Log::info('[PgService] Data directory initialized successfully.');
    }

    protected function removeDirectory(string $path): void
    {
        if (! is_dir($path)) {
            return;
        }
    
        $items = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
    
        foreach ($items as $item) {
            $item->isDir() ? rmdir($item->getRealPath()) : unlink($item->getRealPath());
        }
    
        rmdir($path);
    }

    /**
     * Patch postgresql.conf untuk set port dan listen_addresses.
     */
    protected function patchPostgresConf(): void
    {
        $confFile = $this->pgDataPath . DIRECTORY_SEPARATOR . 'postgresql.conf';

        if (! file_exists($confFile)) {
            throw new RuntimeException("postgresql.conf not found at: {$confFile}");
        }

        $conf = file_get_contents($confFile);

        // Set port
        $conf = preg_replace('/^#?port\s*=.*/m', "port = {$this->pgPort}", $conf);

        // Listen hanya localhost (aman untuk desktop app)
        $conf = preg_replace('/^#?listen_addresses\s*=.*/m', "listen_addresses = 'localhost'", $conf);

        file_put_contents($confFile, $conf);

        Log::info("[PgService] postgresql.conf patched: port={$this->pgPort}, listen_addresses=localhost");
    }

    /**
     * Buat database aplikasi setelah initdb menggunakan createdb.
     * Perlu start PostgreSQL sementara via pg_ctl untuk bisa menjalankan createdb.
     */
    protected function createAppDatabase(): void
    {
        $pgCtl   = $this->bin('pg_ctl');
        $logFile = $this->pgDataPath . DIRECTORY_SEPARATOR . 'pg_init.log';

        // JANGAN pakai runCommand() di sini — exec() selalu blocking di Windows
        // Pakai proc_open agar benar-benar detached
        Log::info('[PgService] Starting PostgreSQL (detached via proc_open)...');

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['file', $logFile, 'a'],
            2 => ['file', $logFile, 'a'],
        ];

        $cmd     = "\"{$pgCtl}\" start -D \"{$this->pgDataPath}\"";
        $process = proc_open($cmd, $descriptors, $pipes);

        if (! is_resource($process)) {
            throw new RuntimeException('Failed to spawn pg_ctl via proc_open.');
        }

        fclose($pipes[0]);
        proc_close($process); // lepas — proses jalan di background

        // Polling port sampai ready
        Log::info('[PgService] Polling port ' . $this->pgPort . '...');
        $ready  = false;
        $waited = 0;

        while ($waited < 30) {
            sleep(1);
            $waited++;
            $conn = @fsockopen('127.0.0.1', $this->pgPort, $errno, $errstr, 1);
            if ($conn) {
                fclose($conn);
                $ready = true;
                Log::info("[PgService] PostgreSQL ready after {$waited}s.");
                break;
            }
            Log::debug("[PgService] Waiting... ({$waited}s) errno={$errno} {$errstr}");
        }

        if (! $ready) {
            $pgLog = file_exists($logFile) ? file_get_contents($logFile) : '(no log)';
            throw new RuntimeException("PostgreSQL tidak ready dalam 30s.\n\n--- pg_init.log ---\n{$pgLog}");
        }

        // Buat database
        $createdb = $this->bin('createdb');
        $out      = [];

        // Set via putenv agar tersedia untuk child process
        putenv("PGPASSWORD={$this->pgPassword}");
        $cmd = "\"{$createdb}\" -h localhost -p {$this->pgPort} -U {$this->pgUser} {$this->pgDatabase}";
        $this->runCommand($cmd, $out);
        putenv('PGPASSWORD');

        Log::info('[PgService] createdb: ' . implode(' | ', $out));

        // Stop temp instance
        $out = [];
        $cmd = "\"{$pgCtl}\" stop -D \"{$this->pgDataPath}\" -m fast";
        $this->runCommand($cmd, $out);
        Log::info('[PgService] pg_ctl stop: ' . implode(' | ', $out));

        Log::info("[PgService] Database '{$this->pgDatabase}' created.");
    }

    /**
     * Daftarkan PostgreSQL sebagai Windows Service menggunakan pg_ctl register.
     */
    protected function registerServiceIfNeeded(): void
    {
        if ($this->isServiceRegistered()) {
            Log::info("[PgService] Service '{$this->serviceName}' already registered.");
            return;
        }

        Log::info("[PgService] Registering Windows Service '{$this->serviceName}'...");

        $pgCtl = $this->bin('pg_ctl');

        // pg_ctl register mendaftarkan PostgreSQL sebagai Windows Service
        // Perlu dijalankan sebagai Administrator saat pertama kali install
        $cmd = "\"{$pgCtl}\" register "
            . "-N \"{$this->serviceName}\" "
            . "-D \"{$this->pgDataPath}\" "
            . "-S auto ";  // auto = start otomatis saat Windows boot
        $output = [];

        $result = $this->runCommand($cmd, $output);

        if ($result !== 0) {
            $msg = implode("\n", $output);
            // Jika error karena tidak ada hak admin, beri pesan yang jelas
            if (str_contains($msg, 'Access is denied') || str_contains($msg, 'OpenSCManager')) {
                throw new RuntimeException(
                    "Gagal mendaftar Windows Service karena kurang hak Administrator.\n"
                    . "Jalankan aplikasi sekali sebagai Administrator untuk instalasi awal.\n"
                    . "Detail: {$msg}"
                );
            }
            throw new RuntimeException("pg_ctl register failed:\n{$msg}");
        }

        Log::info("[PgService] Service '{$this->serviceName}' registered successfully.");
    }

    /**
     * Start Windows Service jika belum running.
     */
    protected function startServiceIfNotRunning(): void
    {
        if ($this->isServiceRunning()) {
            Log::info("[PgService] Service '{$this->serviceName}' is already running.");
            return;
        }

        Log::info("[PgService] Starting service '{$this->serviceName}'...");

        $name   = $this->serviceName;
        $result = $this->runCommand("sc start \"{$name}\"", $output);  // ← quotes!

        if ($result !== 0) {
            throw new RuntimeException(
                "Failed to start service '{$this->serviceName}':\n"
                . implode("\n", $output)
            );
        }

        $waited = 0;
        while (! $this->isServiceRunning() && $waited < 15) {
            sleep(1);
            $waited++;
        }

        if (! $this->isServiceRunning()) {
            throw new RuntimeException("Service '{$this->serviceName}' did not start within 15 seconds.");
        }

        Log::info("[PgService] Service '{$this->serviceName}' started.");
    }

    public function stopPostgres(): void
    {
        if (! $this->isRunning()) {
            return;
        }

        $pgCtl = $this->bin('pg_ctl');
        $out   = [];
        $this->runCommand("\"{$pgCtl}\" stop -D \"{$this->pgDataPath}\" -m fast", $out);
        Log::info('[PgService] PostgreSQL stopped: ' . implode(' ', $out));
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Dapatkan path lengkap ke executable PostgreSQL.
     */
    protected function bin(string $executable): string
    {
        $ext  = $this->isWindows() ? '.exe' : '';
        $path = rtrim($this->pgBinPath, '\\/') . DIRECTORY_SEPARATOR . $executable . $ext;

        if (! file_exists($path)) {
            throw new RuntimeException("PostgreSQL binary not found: {$path}");
        }

        return $path;
    }

    /**
     * Jalankan shell command, return exit code.
     *
     * @param  string   $cmd
     * @param  array    $output  (by reference) output baris per baris
     */
    protected function runCommand(string $cmd, ?array &$output = null): int
    {
        $buf = [];
        Log::debug("[PgService] Running: {$cmd}");
        exec($cmd . ' 2>&1', $buf, $exitCode);
        Log::debug("[PgService] Exit code: {$exitCode}", $buf);
        if ($output !== null) {
            $output = $buf;
        }
        return $exitCode;
    }

    protected function isWindows(): bool
    {
        return PHP_OS_FAMILY === 'Windows';
    }
}
