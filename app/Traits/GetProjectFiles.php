<?php

namespace App\Traits;

use App\Models\File;

trait GetProjectFiles
{

    public function getCurrentFilesCursorForProject($projectId)
    {
        return File::with('directory')
                   ->where('project_id', $projectId)
                   ->where('current', true)
                   ->cursor();
    }
}
