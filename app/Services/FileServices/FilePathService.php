<?php

namespace App\Services\FileServices;

use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FilePathService
{
    public function getFullPath(File $file): ?string
    {
        if ($file->isDir()) {
            return $file->path;
        }

        if (!isset($file->directory)) {
            return "/".$file->name;
        }

        if ($file->directory->path == "/") {
            return "/".$file->name;
        }

        return $file->directory->path."/".$file->name;
    }

    public function getDirPath(File $file): ?string
    {
        if ($file->isDir()) {
            return $file->path;
        }

        return $file->directory->path;
    }

    public function getFilePath(File $file): string
    {
        if (!isset($file->directory)) {
            return $file->name;
        }

        if ($file->directory->path == "/") {
            return $file->directory->path.$file->name;
        }

        return $file->directory->path."/".$file->name;
    }

    public function getMcfsPath(File $file): string
    {
        return Storage::disk('mcfs')->path($this->getRealPathPartial($file));
    }

    public function getMcfsReplicaPath(File $file): string
    {
        return Storage::disk('mcfs_replica')->path($this->getRealPathPartial($file));
    }

    public function getRealPathPartial(File $file): string
    {
        $uuid = $this->getFileUuidToUse($file);
        $dirPath = $this->getPathDirPartial($file);
        return "{$dirPath}/{$uuid}";
    }

    public function getPathDirPartial(File $file): string
    {
        $uuid = $this->getFileUuidToUse($file);
        $entries = explode('-', $uuid);
        $entry1 = $entries[1];

        return "{$entry1[0]}{$entry1[1]}/{$entry1[2]}{$entry1[3]}";
    }

    public function getConvertedPathPartial(File $file): string
    {
        $fileName = $this->getFileUuidToUse($file);

        if ($file->isConvertibleImage()) {
            $fileName = $fileName.".jpg";
        }

        if ($file->isConvertibleOfficeDocument()) {
            $fileName = $fileName.".pdf";
        }

        if ($file->isJupyterNotebook()) {
            $fileName = $fileName.".html";
        }

        return $this->getPathDirPartial($file)."/.conversion/{$fileName}";
    }

    public function getThumbnailPathPartial(File $file): string
    {
        $fileName = $this->getFileUuidToUse($file).".thumb.jpg";
        return $this->getPathDirPartial($file)."/.thumbnails/{$fileName}";
    }

    public function getPartialReplicaPath(File $file): string
    {
        $realPartial = $this->getRealPathPartial($file);
        return "replica/{$realPartial}";
    }

    public function getProjectPathDirPartial(File $file): string
    {
        $dirGroup = intdiv($file->id, 10);
        return "projects/{$dirGroup}/{$file->project_id}";
    }

    public function getDirPathForFormatting(File $file): string
    {
        if (is_null($file->path)) {
            return "";
        }

        if ($file->path === "/") {
            return "";
        }

        return $file->path;
    }

    public function getFileUuidToUse(File $file): string
    {
        return !blank($file->uses_uuid) ? $file->uses_uuid : $file->uuid;
    }
}
