<?php

namespace App\Services\FileServices;

use App\Models\File;

/**
 * Thin wrapper to expose the naming requested in Target Architecture,
 * delegating to existing FilePathService to avoid duplication.
 */
class FilePathResolver
{
    public function __construct(private FilePathService $paths) {}

    public function realPathPartial(File $file): string
    {
        return $this->paths->getRealPathPartial($file);
    }

    public function pathDirPartial(File $file): string
    {
        return $this->paths->getPathDirPartial($file);
    }

    public function convertedPathPartial(File $file): string
    {
        return $this->paths->getConvertedPathPartial($file);
    }

    public function thumbnailPathPartial(File $file): string
    {
        return $this->paths->getThumbnailPathPartial($file);
    }

    public function partialReplicaPath(File $file): string
    {
        return $this->paths->getPartialReplicaPath($file);
    }

    public function projectPathDirPartial(File $file): string
    {
        return $this->paths->getProjectPathDirPartial($file);
    }
}
