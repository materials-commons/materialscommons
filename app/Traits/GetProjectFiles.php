<?php

namespace App\Traits;

use App\Models\File;

trait GetProjectFiles
{

    public function getCurrentFilesCursorForProject($projectId)
    {
        return File::with('directory')
                   ->where('project_id', $projectId)
                   ->active()
                   ->cursor();
    }

    public function getFilesCursorForProject($projectId)
    {
        return File::with('directory')
                   ->where('project_id', $projectId)
                   ->active()
                   ->cursor();
    }

    public function getFilesCursorForProjectAndDataset($projectId, $datasetId)
    {
        return File::with('directory')
                   ->where('project_id', $projectId)
                   ->where('dataset_id', $datasetId)
                   ->whereNull('deleted_at')
                   ->where('current', true)
                   ->cursor();
    }
}
