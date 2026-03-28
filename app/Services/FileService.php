<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
    /**
     * Загрузить файл.
     *
     * @param UploadedFile $file
     * @param string $path
     * @return string
     */
    public function upload(UploadedFile $file, string $path = 'uploads'): string
    {
        return Storage::disk('public')->putFile($path, $file);
    }

    /**
     * Получить url для файла.
     *
     * @param ?string $path
     * @return ?string
     */
    public function url(?string $path): ?string
    {
        return $path ? Storage::disk('public')->url($path) : null;
    }

    /**
     * Удалить файл.
     *
     * @param string $path
     * @return void
     */
    public function delete(string $path): void
    {
        Storage::disk('public')->delete($path);
    }
}
