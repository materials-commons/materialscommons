<?php

namespace App\Traits;

use App\Models\File;

trait GetProjectFolderFiles
{
    public function getProjectFolderFiles($projectId, $folder)
    {
        if ($folder == '/') {
            $rootId = File::where('project_id', $projectId)
                          ->where('name', '/')
                          ->first()->id;
            return File::where('project_id', $projectId)
                       ->where('directory_id', $rootId)
                       ->active()
                       ->get();
        }

        return File::where('project_id', $projectId)
                   ->where('directory_id', $folder)
                   ->where('current', true)
                   ->whereNull('deleted_at')
                   ->whereNull('dataset_id')
                   ->get();
    }

    public function getProjectFolders($projectId)
    {
        return File::where('project_id', $projectId)
                   ->directories()
                   ->active()
                   ->get();
    }
}
