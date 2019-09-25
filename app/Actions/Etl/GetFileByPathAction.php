<?php

namespace App\Actions\Etl;

use App\Models\File;
use App\Models\Project;

class GetFileByPathAction
{
    use ProjectNameFilePath;

    // Look up file by path. This controller expects the path to start with the project name. This is done
    // to retain compatibility with the old NodeJS based API.
    public function __invoke($projectId, $filePath)
    {
        $project = Project::findOrFail($projectId);
        $pathWithoutProject = $this->removeProjectNameFromPath($filePath, strlen($project->name));
        $fileName = basename($pathWithoutProject);
        $dirName = dirname($pathWithoutProject);
        $dir = File::where('project_id', $projectId)->where('path', $dirName)->first();
        $file = File::where('directory_id', $dir->id)->where('name', $fileName)->where('current', true)->first();
        return $file;
    }

}