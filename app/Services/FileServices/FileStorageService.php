<?php

namespace App\Services\FileServices;

use Illuminate\Support\Facades\Storage;

class FileStorageService
{
    public function exists(string $disk, string $path): bool
    {
        return Storage::disk($disk)->exists($path);
    }

    /**
     * @param string $disk
     * @param string $path
     * @param string|resource $contents
     */
    public function put(string $disk, string $path, $contents): void
    {
        Storage::disk($disk)->put($path, $contents);
    }

    public function copy(string $fromDisk, string $fromPath, string $toDisk, string $toPath): void
    {
        if ($fromDisk === $toDisk) {
            Storage::disk($fromDisk)->copy($fromPath, $toPath);
            return;
        }
        // Cross-disk copy via get/put stream where needed
        $stream = Storage::disk($fromDisk)->readStream($fromPath);
        if ($stream === false) {
            // Fall back to string get
            $contents = Storage::disk($fromDisk)->get($fromPath);
            Storage::disk($toDisk)->put($toPath, $contents);
            return;
        }
        Storage::disk($toDisk)->put($toPath, $stream);
        if (is_resource($stream)) {
            fclose($stream);
        }
    }

    public function path(string $disk, string $path): string
    {
        return Storage::disk($disk)->path($path);
    }
}
