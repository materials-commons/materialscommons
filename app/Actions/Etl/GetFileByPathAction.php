<?php

namespace App\Actions\Etl;

use App\Models\File;
use App\Models\Project;

class GetFileByPathAction
{
    use ProjectNameFilePath;

    // Look up file by path. This controller expects the path to start with the project name. This is done
    // to retain compatibility with the old NodeJS based API.
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
        return File::where('project_id', $projectId)->where('path', '/')->first();
    }

    private function getFileOrDirAtPath($path, $projectId)
    {
        $fileName = basename($path);
        $dirName = dirname($path);
        $dir = File::where('project_id', $projectId)
                   ->where('path', $dirName)
                   ->where('current', true)
                   ->first();
        if (is_null($dir)) {
            return null;
        }

        return File::where('directory_id', $dir->id)
                   ->where('name', $fileName)
                   ->where('current', true)
                   ->first();
    }
}