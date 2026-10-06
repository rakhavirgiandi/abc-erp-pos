<?php

namespace App\Support;

use Illuminate\Support\Arr;

class LocalSettings
{
    protected ?array $data = null;

    public function __construct(protected string $path) {}

    protected static ?self $instance = null;

    public static function instance(): static
    {
        return static::$instance ??= new static(storage_path('app/settings.json'));
    }

    public function all(): array
    {
        return $this->data ??= is_file($this->path)
            ? (json_decode(file_get_contents($this->path), true) ?: [])
            : [];
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->all(), $key, $default);
    }

    public function has(string $key): bool
    {
        return Arr::has($this->all(), $key);
    }

    public function set(string|array $key, mixed $value = null): void
    {
        $data = $this->all();

        foreach (is_array($key) ? $key : [$key => $value] as $k => $v) {
            Arr::set($data, $k, $v);
        }

        $this->write($data);
    }

    public function forget(string $key): void
    {
        $data = $this->all();
        Arr::forget($data, $key);
        $this->write($data);
    }

    protected function write(array $data): void
    {
        @mkdir(dirname($this->path), 0755, true);

        $tmp = $this->path . '.tmp';
        file_put_contents(
            $tmp,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            LOCK_EX
        );
        rename($tmp, $this->path); // tulis atomik, file tidak korup kalau app tertutup mendadak

        $this->data = $data;
    }
}