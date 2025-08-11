<?php

namespace App\Traits;

use App\Actions\Directories\CreateDirectoryAction;
use App\Helpers\PathHelpers;
use App\Models\File;
use App\Models\Project;
use Exception;
use function basename;
use function blank;
use function dirname;
use function explode;
use function is_null;

trait CreateDirectories
{
    private function getOrCreateSingleDirectoryIfDoesNotExist(File $baseDir, string $path, Project $project, $ownerId)
    {
        $dir = File::where('project_id', $project->id)
                   ->where('path', $path)
                   ->where('mime_type', 'directory')
                   ->whereNull('dataset_id')
                   ->whereNull('deleted_at')
                   ->where('current', true)
                   ->first();
        if ($dir !== null) {
            return $dir;
        }


        try {
            return File::create([
                'name'         => basename($path),
                'path'         => $path,
                'mime_type'    => 'directory',
                'owner_id'     => $ownerId,
                'project_id'   => $project->id,
                'current'      => true,
                'directory_id' => optional($baseDir)->id,
            ]);
        } catch (Exception $e) {
            // Exception, so maybe dir exists, so try getting it again.
            return File::where('project_id', $project->id)
                       ->where('path', $path)
                       ->where('mime_type', 'directory')
                       ->whereNull('dataset_id')
                       ->whereNull('deleted_at')
                       ->where('current', true)
                       ->first();
        }

    }

    private function getDirectoryOrCreateIfDoesNotExist(File $baseDir, string $path, Project $project)
    {
        $dir = $this->getDirectory($baseDir->path, $path, $project->id);
        if (!is_null($dir)) {
            return $dir;
        }

        $this->makeDirPathInProject($baseDir, $path, $project);

        return $this->getDirectory($baseDir->path, $path, $project->id);
    }

    private function getDirectory($rootDirPath, $path, int $projectId)
    {
        $pathToUse = PathHelpers::normalizePath("/{$rootDirPath}/{$path}");
        return File::where('project_id', $projectId)
                   ->where('path', $pathToUse)
                   ->where('mime_type', 'directory')
                   ->whereNull('deleted_at')
                   ->whereNull('dataset_id')
                   ->where('current', true)
                   ->first();
    }

    /**
     * makeDirPathInProject will break a path into its individual parts and then create the
     * whole chain of directories. It checks if each part already exists.
     * @param  \App\Models\File  $rootDir
     * @param  string  $path
     * @param  \App\Models\Project  $project
     */
    private function makeDirPathInProject(File $rootDir, string $path, Project $project)
    {
        $pathToCreate = "";
        foreach (explode('/', $path) as $pathItem) {
            if (blank($pathItem)) {
                continue;
            }
            $pathToCreate = "{$pathToCreate}/{$pathItem}";
            if ($this->getDirectory($rootDir->path, $pathToCreate, $project->id) == null) {
                $parent = $this->getDirectory($rootDir->path, dirname($pathToCreate), $project->id);
                $this->makeDirInProject($parent, $pathToCreate, $project);
            }
        }
    }

    private function makeDirInProject(File $parentDir, string $path, Project $project)
    {
        $data = [
            'name'         => basename($path),
            'project_id'   => $project->id,
            'directory_id' => $parentDir->id,
        ];

        $createDirectoryAction = new CreateDirectoryAction();
        return $createDirectoryAction->execute($data, $project->owner_id);
    }
}
