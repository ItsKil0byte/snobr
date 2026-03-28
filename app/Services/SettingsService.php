<?php

namespace App\Services;

// Используем чтобы было меньше возни с вложенностью.
use Illuminate\Support\Arr;

class SettingsService
{
    private array $settings = [];
    private string $path;

    public function __construct(string $path)
    {
        if (file_exists($path)) {
            $file = file_get_contents($path);
            $this->settings = json_decode($file, true) ?: [];
            $this->path = $path;
        }
    }

    /**
     * Получить значение ключа из файла настроек.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->settings, $key, $default);
    }

    /**
     * Установить значение по ключу в файле настроек.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        Arr::set($this->settings, $key, $value);

        file_put_contents(
            $this->path,
            json_encode(
                $this->settings,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE |
                JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK
            )
        );

        // https://www.php.net/manual/en/json.constants.php#119565
    }
}
