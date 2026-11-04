<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use RuntimeException;

class PostgresWindowsService
{
    protected string $serviceName;
    protected string $pgBinPath;
    protected string $pgDataPath;
    protected int    $pgPort;
    protected string $pgUser;
    protected string $pgPassword;
    protected string $pgDatabase;

    public function __construct()
    {
        $this->serviceName = config('services.pgsql.service_name', 'ABC POS PostgreSQL');
        $this->pgUser      = config('services.pgsql.superuser', 'abc_pos_postgres');
        $this->pgPassword  = config('services.pgsql.password', 'root');
        $this->pgDatabase  = config('services.pgsql.database', 'abc_pos_db');

        $this->pgBinPath  = $this->resolvePgsqlBinPath();
        $this->pgDataPath = $this->resolvePgsqlDataPath();

        // Prioritas port:
        // 1. localSettings (disimpan saat init, sumber kebenaran)
        // 2. postgresql.auto.conf / postgresql.conf (instalasi lama yang belum punya setting)
        // 3. registry (ditulis installer)
        // 4. config
        $this->pgPort = $this->storedPort()
            ?? $this->resolvePortFromDataDir()
            ?? $this->resolvePortFromRegistry()
            ?? (int) config('services.pgsql.port', 1933);
    }

    // =========================================================================
    // Port Resolution (sumber awal)
    // =========================================================================

    protected function storedPort(): ?int
    {
        try {
            $port = (int) localSettings()->get('database.port');
            return $port > 0 ? $port : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function resolvePortFromDataDir(): ?int
    {
        // auto.conf menimpa postgresql.conf, jadi dicek lebih dulu
        foreach (['postgresql.auto.conf', 'postgresql.conf'] as $file) {
            $path = $this->pgDataPath . DIRECTORY_SEPARATOR . $file;
            if (is_file($path)
                && preg_match('/^\s*port\s*=\s*(\d+)/m', file_get_contents($path), $m)) {
                return (int) $m[1];
            }
        }
        return null;
    }

    protected function resolvePortFromRegistry(): ?int
    {
        $regValue = shell_exec('reg query "HKLM\Software\ABCPOS" /v PgPort 2>&1');
        if ($regValue && preg_match('/PgPort\s+REG_SZ\s+(\d+)/i', $regValue, $m)) {
            return (int) $m[1];
        }
        return null;
    }

    // =========================================================================
    // Public API
    // =========================================================================

    public function ensureRunning(): array
    {
        if (! $this->isWindows()) {
            return $this->readyResponse('Non-Windows environment, menggunakan PostgreSQL sistem.');
        }

        $initialized = $this->isDataDirInitialized();
        $portChanged = false;
        $originalPort = $this->pgPort;

        if (! $initialized) {
            // Satu-satunya tempat port boleh dicari: saat init pertama
            [$portChanged, $originalPort] = $this->choosePortForInit();
        } else {
            // Setelah init: port tetap, ikuti localSettings
            if ($this->storedPort() === null) {
                localSettings()->set('database.port', $this->pgPort);
            }
            $this->syncPortToConf();
        }

        if ($initialized && $this->isPortOpen($this->pgPort)) {
            Log::info("[PgService] PostgreSQL sudah berjalan di port {$this->pgPort}.");
            return $this->readyResponse(
                "PostgreSQL sudah berjalan di port {$this->pgPort}.",
                $this->pgPort, false, null
            );
        }

        // Init → register → start
        $this->initDataDirectoryIfNeeded();
        $this->registerServiceIfNeeded();
        $this->startServiceIfNotRunning();

        return $this->readyResponse(
            "PostgreSQL berhasil dijalankan di port {$this->pgPort}.",
            $this->pgPort, $portChanged, $portChanged ? $originalPort : null
        );
    }

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

    public function isServiceRunning(): bool
    {
        $output = shell_exec("sc query \"{$this->serviceName}\" 2>&1");
        return $output !== null && str_contains($output, 'RUNNING');
    }

    public function isServiceRegistered(): bool
    {
        $output = shell_exec("sc query \"{$this->serviceName}\" 2>&1");
        return $output !== null && ! str_contains($output, 'FAILED 1060');
    }

    public function isPortOpen(int $port): bool
    {
        $conn = @fsockopen('127.0.0.1', $port, $errno, $errstr, 1);
        if ($conn) { fclose($conn); return true; }
        return false;
    }

    public function getPort(): int        { return $this->pgPort; }
    public function getDatabase(): string { return $this->pgDatabase; }
    public function getUsername(): string { return $this->pgUser; }
    public function getPassword(): string { return $this->pgPassword; }

    public function getDataPath(): string { return $this->pgDataPath; }
    public function getBinPath(): string  { return $this->pgBinPath; }

    public function getConnectionInfo(): array
    {
        return [
            'host'     => '127.0.0.1',
            'port'     => $this->pgPort,
            'database' => $this->pgDatabase,
            'username' => $this->pgUser,
            'password' => $this->pgPassword,
        ];
    }

    // =========================================================================
    // Path Resolution
    // =========================================================================

    protected function grantServiceAccountAccess(): void
    {
        $this->runCommand('icacls "' . $this->pgDataPath . '" /grant "NT AUTHORITY\\NetworkService:(OI)(CI)F" /T /Q');
    }

    protected function resolvePgsqlBinPath(): string
    {
        // Prioritas 1: registry (ditulis oleh installer)
        $regValue = shell_exec('reg query "HKLM\Software\ABCPOS" /v PgBinPath 2>&1');
        if ($regValue && preg_match('/PgBinPath\s+REG_SZ\s+(.+)/i', $regValue, $m)) {
            $path = trim($m[1]);
            if (file_exists($path . DIRECTORY_SEPARATOR . 'pg_ctl.exe')) {
                Log::info("[PgService] pg binary from registry: {$path}");
                return $path;
            }
        }

        // Prioritas 2: __DIR__ relatif (production NativePHP)
        $candidates = [
            dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'pgsql' . DIRECTORY_SEPARATOR . 'bin',
            base_path('pgsql' . DIRECTORY_SEPARATOR . 'bin'),
        ];

        foreach ($candidates as $path) {
            $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
            if (file_exists($path . DIRECTORY_SEPARATOR . 'pg_ctl.exe') ||
                file_exists($path . DIRECTORY_SEPARATOR . 'pg_ctl')) {
                Log::info("[PgService] pg binary found: {$path}");
                return $path;
            }
        }

        return str_replace('/', DIRECTORY_SEPARATOR, base_path('pgsql/bin'));
    }

    protected function resolvePgsqlDataPath(): string
    {
        $regValue = shell_exec('reg query "HKLM\Software\ABCPOS" /v DataPath 2>&1');
        if ($regValue && preg_match('/DataPath\s+REG_SZ\s+(.+)/i', $regValue, $m)) {
            return trim($m[1]);
        }

        return storage_path('pgsql' . DIRECTORY_SEPARATOR . 'data');
    }

    // =========================================================================
    // Port Management
    // =========================================================================

    protected function isDataDirInitialized(): bool
    {
        return file_exists($this->pgDataPath . DIRECTORY_SEPARATOR . 'PG_VERSION');
    }

    /**
     * Dipanggil hanya saat data directory belum ada (init pertama).
     * Pilih port: pakai port preferensi kalau bebas, kalau tidak cari yang kosong.
     * Hasilnya disimpan ke localSettings dan .env.
     *
     * Return [portChanged, originalPort]
     */
    protected function choosePortForInit(): array
    {
        $originalPort = $this->pgPort;
        $portChanged  = false;

        if ($this->isPortUsedByOther($this->pgPort)) {
            $newPort = $this->findAvailablePort($this->pgPort + 1);
            Log::warning("[PgService] Init: port {$this->pgPort} dipakai proses lain. Beralih ke {$newPort}.");

            $this->pgPort = $newPort;
            $this->updateEnvPort($newPort);
            $portChanged = true;

            try {
                app('db')->purge('pgsql');
                app('db')->purge('pgsql_companies');
            } catch (\Throwable $e) {
                Log::warning('[PgService] Gagal purge koneksi lama: ' . $e->getMessage());
            }
        }

        localSettings()->set('database.port', $this->pgPort);
        Log::info("[PgService] Init: port {$this->pgPort} disimpan ke localSettings.");

        // patchPostgresConf() akan menulis port ini ke postgresql.conf saat initdb
        return [$portChanged, $originalPort];
    }

    /**
     * Pastikan postgresql.conf / auto.conf memakai port dari localSettings.
     * Tidak mencari port baru, hanya menyamakan file dengan localSettings.
     */
    protected function syncPortToConf(): void
    {
        // postgresql.conf: selalu pastikan ada baris port aktif
        $conf = $this->pgDataPath . DIRECTORY_SEPARATOR . 'postgresql.conf';
        if (is_file($conf)) {
            $c = file_get_contents($conf);
            $new = preg_match('/^\s*port\s*=/m', $c)
                ? preg_replace('/^\s*port\s*=.*/m', "port = {$this->pgPort}", $c)
                : (preg_match('/^#\s*port\s*=/m', $c)
                    ? preg_replace('/^#\s*port\s*=.*/m', "port = {$this->pgPort}", $c, 1)
                    : $c . "\nport = {$this->pgPort}\n");
            if ($new !== $c) {
                file_put_contents($conf, $new);
                Log::info("[PgService] postgresql.conf disinkronkan ke port {$this->pgPort}.");
            }
        }
    
        // auto.conf: hapus baris port supaya tidak menimpa postgresql.conf
        $auto = $this->pgDataPath . DIRECTORY_SEPARATOR . 'postgresql.auto.conf';
        if (is_file($auto)) {
            $a = file_get_contents($auto);
            $new = preg_replace('/^\s*port\s*=.*\R?/m', '', $a);
            if ($new !== $a) {
                file_put_contents($auto, $new);
                Log::info('[PgService] Baris port dihapus dari postgresql.auto.conf.');
            }
        }
    }

    protected function isPortUsedByOther(int $port): bool
    {
        $output = shell_exec('netstat -ano -p TCP 2>&1') ?? '';

        foreach (preg_split('/\R/', $output) as $line) {
            if (! preg_match('/^\s*TCP\s+\S+:' . $port . '\s+\S+\s+LISTENING\s+(\d+)/i', $line, $m)) {
                continue;
            }

            // Dipegang service Postgres kita sendiri → bukan konflik
            $sc = shell_exec("sc queryex \"{$this->serviceName}\" 2>&1") ?? '';
            if (preg_match('/PID\s*:\s*' . $m[1] . '\b/', $sc)) {
                return false;
            }

            $proc = shell_exec("tasklist /FI \"PID eq {$m[1]}\" /FO CSV /NH 2>&1");
            if ($proc && str_contains(strtolower($proc), 'postgres')) {
                return false;
            }

            Log::warning("[PgService] Port {$port} dipegang PID {$m[1]}: " . trim($proc ?? '(tasklist kosong)'));
            return true;
        }
        return false;
    }

    protected function findAvailablePort(int $startPort = 5434): int
    {
        for ($port = $startPort; $port < 65535; $port++) {
            $conn = @fsockopen('127.0.0.1', $port, $errno, $errstr, 0.5);
            if (! $conn) return $port;
            fclose($conn);
        }
        throw new RuntimeException('Tidak ada port yang tersedia untuk PostgreSQL.');
    }

    protected function updateEnvPort(int $newPort): void
    {
        // Development: skip — jangan ubah .env yang akan ter-bundle saat build
        if (app()->environment('local', 'development')) {
            Log::info('[PgService] Dev mode — skip .env update.');
            return;
        }

        $envFile = app()->environmentFilePath();
        if (! file_exists($envFile)) return;

        $content = file_get_contents($envFile);
        $content = preg_match('/^DB_PORT=/m', $content)
            ? preg_replace('/^DB_PORT=.*/m', "DB_PORT={$newPort}", $content)
            : $content . "\nDB_PORT={$newPort}\n";

        file_put_contents($envFile, $content);
        Log::info("[PgService] .env: DB_PORT={$newPort}");
    }

    // =========================================================================
    // Init & Setup
    // =========================================================================

    protected function initDataDirectoryIfNeeded(): void
    {
        if ($this->isDataDirInitialized()) {
            Log::info('[PgService] Data directory already initialized.');
            return;
        }

        // Hapus folder nanggung jika ada
        if (is_dir($this->pgDataPath)) {
            Log::info('[PgService] Removing stale data directory...');
            exec('cmd /c rd /s /q "' . $this->pgDataPath . '"');
            sleep(1);
        }

        Log::info('[PgService] Initializing data directory...');

        $parentDir = dirname($this->pgDataPath);
        if (! is_dir($parentDir)) {
            mkdir($parentDir, 0755, true);
        }

        // Tulis password ke file sementara di parent (bukan di data dir)
        $pwFile = $parentDir . DIRECTORY_SEPARATOR . 'pwfile.tmp';
        file_put_contents($pwFile, $this->pgPassword);

        $output = [];
        $result = $this->runCommand(
            "\"{$this->bin('initdb')}\" -D \"{$this->pgDataPath}\" -U \"{$this->pgUser}\" "
            . "--pwfile=\"{$pwFile}\" --encoding=UTF8 --locale=C -A md5",
            $output
        );
        @unlink($pwFile);

        if ($result !== 0) {
            throw new RuntimeException("initdb failed:\n" . implode("\n", $output));
        }

        $this->grantServiceAccountAccess();
        $this->patchPostgresConf();
        $this->createAppDatabase();

        Log::info('[PgService] Data directory initialized.');
    }

    protected function patchPostgresConf(): void
    {
        $confFile = $this->pgDataPath . DIRECTORY_SEPARATOR . 'postgresql.conf';
        if (! file_exists($confFile)) {
            throw new RuntimeException("postgresql.conf not found: {$confFile}");
        }

        $conf = file_get_contents($confFile);
        $conf = preg_replace('/^#?port\s*=.*/m',             "port = {$this->pgPort}",   $conf);
        $conf = preg_replace('/^#?listen_addresses\s*=.*/m', "listen_addresses = 'localhost'", $conf);
        file_put_contents($confFile, $conf);

        Log::info("[PgService] postgresql.conf patched: port={$this->pgPort}");
    }

    protected function createAppDatabase(): void
    {
        $pgCtl   = $this->bin('pg_ctl');
        $logFile = $this->pgDataPath . DIRECTORY_SEPARATOR . 'pg_init.log';

        // Start sementara — detached via proc_open agar tidak blocking
        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['file', $logFile, 'a'],
            2 => ['file', $logFile, 'a'],
        ];
        $process = proc_open("\"{$pgCtl}\" start -D \"{$this->pgDataPath}\"", $descriptors, $pipes);
        if (! is_resource($process)) {
            throw new RuntimeException('Failed to spawn pg_ctl.');
        }
        fclose($pipes[0]);
        proc_close($process);

        // Polling sampai siap
        $ready = false;
        for ($i = 1; $i <= 30; $i++) {
            sleep(1);
            if ($this->isPortOpen($this->pgPort)) {
                Log::info("[PgService] PostgreSQL ready after {$i}s.");
                $ready = true;
                break;
            }
        }

        if (! $ready) {
            $log = file_exists($logFile) ? file_get_contents($logFile) : '(no log)';
            throw new RuntimeException("PostgreSQL tidak ready dalam 30s.\n\n{$log}");
        }

        // Buat database aplikasi
        $out = [];
        putenv("PGPASSWORD={$this->pgPassword}");
        $this->runCommand(
            "\"{$this->bin('createdb')}\" -h localhost -p {$this->pgPort} -U {$this->pgUser} {$this->pgDatabase}",
            $out
        );
        putenv('PGPASSWORD');
        Log::info('[PgService] createdb: ' . implode(' | ', $out));

        // Stop temp instance
        $out = [];
        $this->runCommand("\"{$pgCtl}\" stop -D \"{$this->pgDataPath}\" -m fast", $out);
        Log::info('[PgService] pg_ctl stop: ' . implode(' | ', $out));
        Log::info("[PgService] Database '{$this->pgDatabase}' created.");
    }

    protected function registerServiceIfNeeded(): void
    {
        if ($this->isServiceRegistered()) {
            Log::info("[PgService] Service already registered.");
            return;
        }

        Log::info("[PgService] Registering Windows Service '{$this->serviceName}'...");

        $pgCtl  = $this->bin('pg_ctl');
        $name   = $this->serviceName;
        $output = [];
        $result = $this->runCommand(
            "\"{$pgCtl}\" register -N \"{$name}\" -D \"{$this->pgDataPath}\" -S auto",
            $output
        );

        if ($result === 0) {
            Log::info("[PgService] Service registered.");
            return;
        }

        $msg = implode("\n", $output);

        // Coba via UAC elevation jika kurang Admin
        if (str_contains($msg, 'could not open service manager') ||
            str_contains($msg, 'Access is denied') ||
            str_contains($msg, 'OpenSCManager')) {

            Log::info('[PgService] Elevating via UAC...');
            $psCmd = "Start-Process -FilePath '\"{$pgCtl}\"' "
                . "-ArgumentList 'register','-N','\"{$name}\"','-D','\"{$this->pgDataPath}\"','-S','auto' "
                . "-Verb RunAs -Wait";

            if ($this->runCommand("powershell -Command \"{$psCmd}\"") === 0) {
                Log::info('[PgService] Service registered via UAC.');
                return;
            }

            throw new RuntimeException(
                "Gagal mendaftarkan Windows Service.\n"
                . "Jalankan aplikasi sekali sebagai Administrator (klik kanan → Run as administrator)."
            );
        }

        throw new RuntimeException("pg_ctl register failed:\n{$msg}");
    }

    protected function startServiceIfNotRunning(): void
    {
        if ($this->isServiceRunning()) {
            if ($this->isPortOpen($this->pgPort)) {
                Log::info("[PgService] Service already running.");
                return;
            }
            Log::info("[PgService] Service jalan di port lain, restart...");
            $this->runCommand("sc stop \"{$this->serviceName}\"");
            for ($i = 0; $i < 15 && $this->isServiceRunning(); $i++) sleep(1);
        }

        Log::info("[PgService] Starting service '{$this->serviceName}'...");

        $output = [];
        $result = $this->runCommand("sc start \"{$this->serviceName}\"", $output);

        if ($result !== 0) {
            throw new RuntimeException(
                "Failed to start service:\n" . implode("\n", $output)
            );
        }

        // Tunggu port terbuka (lebih reliable daripada cek service state)
        for ($i = 0; $i < 20 && ! $this->isPortOpen($this->pgPort); $i++) {
            sleep(1);
        }

        if (! $this->isPortOpen($this->pgPort)) {
            $logs = glob($this->pgDataPath . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . '*.log') ?: [];
            $tail = $logs ? implode('', array_slice(file(end($logs)), -15)) : '(log tidak ditemukan)';

            throw new RuntimeException(
                "Service started but port {$this->pgPort} tidak bisa diakses dalam 20 detik.\n\n{$tail}"
            );
        }

        Log::info("[PgService] Service started on port {$this->pgPort}.");
    }

    public function stopPostgres(): void
    {
        if (! $this->isPortOpen($this->pgPort)) return;

        $out = [];
        $this->runCommand("\"{$this->bin('pg_ctl')}\" stop -D \"{$this->pgDataPath}\" -m fast", $out);
        Log::info('[PgService] PostgreSQL stopped.');
    }

    public function resetData(): array
    {
        $this->removeService();

        if (is_dir($this->pgDataPath)) {
            Log::warning("[PgService] Menghapus data directory: {$this->pgDataPath}");
            exec('cmd /c rd /s /q "' . $this->pgDataPath . '"');
            sleep(1);
        }

        // Data directory hilang → ensureRunning() dianggap init baru dan boleh cari port lagi
        return $this->ensureRunning();
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    protected function bin(string $executable): string
    {
        $path = rtrim($this->pgBinPath, '\\/') . DIRECTORY_SEPARATOR
            . $executable . ($this->isWindows() ? '.exe' : '');

        if (! file_exists($path)) {
            throw new RuntimeException("PostgreSQL binary not found: {$path}");
        }
        return $path;
    }

    protected function runCommand(string $cmd, ?array &$output = null): int
    {
        $buf = [];
        Log::debug("[PgService] Run: {$cmd}");
        exec($cmd . ' 2>&1', $buf, $code);
        Log::debug("[PgService] Exit: {$code}", $buf);
        if ($output !== null) $output = $buf;
        return $code;
    }

    protected function readyResponse(
        string $message,
        int    $port = 0,
        bool   $portChanged = false,
        ?int   $oldPort = null
    ): array {
        return [
            'status'       => 'ready',
            'step'         => 'Database siap.',
            'port'         => $port ?: $this->pgPort,
            'port_changed' => $portChanged,
            'old_port'     => $oldPort,
            'message'      => $message,
        ];
    }

    protected function isWindows(): bool
    {
        return PHP_OS_FAMILY === 'Windows';
    }
}