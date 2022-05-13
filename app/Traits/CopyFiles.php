<?php

namespace App\Traits;

use App\Actions\Directories\CreateDirectoryAction;
use App\Helpers\PathHelpers;
use App\Models\File;
use App\Models\Project;
use Ramsey\Uuid\Uuid;
use function basename;
use function blank;
use function dirname;
use function explode;
use function is_null;

trait CopyFiles
{
    private function getDirectoryOrCreateIfDoesNotExist(File $rootDir, string $path, Project $project)
    {
        $dir = $this->getDirectory($rootDir->name, $path, $project->id);
        if (!is_null($dir)) {
            return $dir;
        }

        $this->makeDirPathInProject($rootDir, $path, $project);

        return $this->getDirectory($rootDir->name, $path, $project->id);
    }

    private function getDirectory($rootDirName, $path, int $projectId)
    {
        if ($path == "/") {
            $pathToUse = "/";
        } else {
            $pathToUse = PathHelpers::normalizePath("/{$rootDirName}/{$path}");
        }
        return File::where('project_id', $projectId)
                   ->where('path', $pathToUse)
                   ->where('mime_type', 'directory')
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
            if ($this->getDirectory($rootDir->name, $pathToCreate, $project->id) == null) {
                $parent = $this->getDirectory($rootDir->name, dirname($pathToCreate), $project->id);
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

    private function importFileIntoDir(File $dir, File $file)
    {
        if (!$file->current) {
            return;
        }
        $usesUuid = blank($file->uses_uuid) ? $file->uuid : $file->uses_uuid;
        $f = $file->replicate()->fill([
            'uuid'         => Uuid::uuid4()->toString(),
            'directory_id' => $dir->id,
            'uses_uuid'    => $usesUuid,
            'owner_id'     => $dir->owner_id,
            'current'      => true,
            'project_id'   => $dir->project_id,
        ]);

        $f->save();
    }

    private function isFileEntry(File $file): bool
    {
        return $file->mime_type !== 'directory';
    }
}