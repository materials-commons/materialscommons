<?php

namespace App\Actions\Etl;

use App\Models\File;
use App\Models\Project;

class GetFileByPathAction
{
    use ProjectNameFilePath;

    // Look up file by path. It works with paths that do and do not contain the project name.
    public function execute($projectId, $filePath)
    {
        $project = Project::findOrFail($projectId);
        $pathWithoutProject = $filePath;
        if ($filePath[0] !== "/") {
            $pathWithoutProject = $this->removeProjectNameFromPath($filePath, strlen($project->name));
        }

        if ($this->isForRoot($pathWithoutProject)) {
            return $this->getRootDir($projectId);
        }

        return $this->getFileOrDirAtPath($pathWithoutProject, $projectId);
    }

    private function isForRoot($path)
    {
        return $path == "/";
    }

    private function getRootDir($projectId)
    {
        return File::where('project_id', $projectId)
                   ->where('current', true)
                   ->whereNull('dataset_id')
                   ->whereNull('deleted_at')
                   ->where('path', '/')
                   ->first();
    }

    private function getFileOrDirAtPath($path, $projectId)
    {
        $fileName = basename($path);
        $dirName = dirname($path);
        $dir = File::where('project_id', $projectId)
                   ->where('path', $dirName)
                   ->whereNull('dataset_id')
                   ->whereNull('deleted_at')
                   ->where('current', true)
                   ->first();
        if (is_null($dir)) {
            return null;
        }

        return File::with('directory')
                   ->where('directory_id', $dir->id)
                   ->where('name', $fileName)
                   ->whereNull('dataset_id')
                   ->whereNull('deleted_at')
                   ->where('current', true)
                   ->first();
    }
}
