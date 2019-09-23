<?php

namespace App\Actions\Etl;

use App\Models\File;
use App\Models\Project;

class GetFileByPathAction
{
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

    /**
     * To remain compatible with the old api paths are assumed to start with the project name. This action
     * will remove the project name and just keep the starting slash as names are stored with a / for the
     * the root rather than the project name.
     */
    private function removeProjectNameFromPath($path, $projectNameLength)
    {
        return substr_replace($path, '', 0, $projectNameLength);
    }
}