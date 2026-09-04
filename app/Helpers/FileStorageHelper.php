<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileStorageHelper
{
    public static function upload(
        UploadedFile $file,
        string $directory
    ): string {
        return $file->store($directory, 'public');
    }

    public static function delete(string $path): void
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}