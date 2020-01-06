<?php

namespace App\Actions\Globus;

use App\Models\File;
use App\Models\User;
use App\Traits\PathFromUUID;
use Exception;
use Illuminate\Support\Str;

class CreateGlobusDownloadForProjectAction
{
    use PathFromUUID;

    public function __invoke($projectId, User $user)
    {
        $allDirs = File::where('project_id', $projectId)
                       ->where('mime_type', 'directory')
                       ->orderBy('path')
                       ->get();

        $baseDir = storage_path("app/__globus_downloads/{$projectId}/{$user->uuid}");

        $dirsToCreate = $this->determineMinimumSetOfDirsToCreate($allDirs);
        $this->createDirs($dirsToCreate, $baseDir);

        foreach ($allDirs as $dir) {
            $files = File::where('directory_id', $dir->id)->whereNull('path')->get();
            foreach ($files as $file) {
                $uuid = $file->uses_uuid ?? $file->uuid;
                $uuidPath = $this->filePathFromUuid($uuid);
                $filePath = "{$baseDir}{$dir->path}/{$file->name}";
                link($uuidPath, $filePath);
            }
        }
    }

    private function determineMinimumSetOfDirsToCreate($allDirs)
    {
        $dirsToKeep = collect();
        $previousDir = $allDirs[0];
        foreach ($allDirs as $dir) {
            if (Str::startsWith($dir->path, $previousDir->path)) {
                $previousDir = $dir;
            } else {
                $dirsToKeep->put($previousDir->path, true);
                $previousDir = $dir;
            }
        }

        $lastDir = $allDirs->last();
        if (!$dirsToKeep->contains($lastDir->path)) {
            $dirsToKeep->put($lastDir->path, true);
        }

        return $dirsToKeep->keys()->all();
    }

    private function createDirs($dirsToKeep, $basePath)
    {
        foreach ($dirsToKeep as $dir) {
            try {
                $path = "{$basePath}{$dir}";
                mkdir($path, 0777, true);
            } catch (Exception $e) {
                // ignore
            }
        }
    }
}