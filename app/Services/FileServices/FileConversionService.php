<?php

namespace App\Services\FileServices;

use App\Models\File;

class FileConversionService
{
    public function __construct(private FilePathResolver $paths, private FileStorageService $storage) {}

    public function shouldConvert(File $file): bool
    {
        if (!$file->isConvertible()) {
            return false;
        }
        $converted = $this->paths->convertedPathPartial($file);
        return !$this->storage->exists('mcfs', $converted);
    }

    public function convertedPath(File $file): string
    {
        return $this->paths->convertedPathPartial($file);
    }

    public function triggerConversion(File $file): void
    {
        // Intentionally blank (no events/listeners as per issue)
    }
}
