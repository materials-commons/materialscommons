<?php

namespace App\Services\FileServices;

use App\Models\File;

class FileThumbnailService
{
    public function __construct(private FilePathResolver $paths, private FileStorageService $storage) {}

    public function shouldGenerate(File $file): bool
    {
        if (!$file->isImage()) {
            return false;
        }
        $thumb = $this->paths->thumbnailPathPartial($file);
        return !$this->storage->exists('mcfs', $thumb);
    }

    public function thumbnailPath(File $file): string
    {
        return $this->paths->thumbnailPathPartial($file);
    }

    public function triggerGeneration(File $file): void
    {
        // Intentionally blank (no events/listeners as per issue)
    }
}
