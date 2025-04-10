<?php

namespace App\Traits\Folders;

use App\Models\Project;
use function is_null;
use function request;

trait DestinationProject
{
    private function getDestinationProjectId(Project $project)
    {
        $destProjectId = request()->get('destproj');
        if (is_null($destProjectId)) {
            return $project->id;
        }
        return $destProjectId;
    }

    private function getDestinationProject(Project $project): Project
    {
        $destProjectId = request()->get('destproj');
        if (is_null($destProjectId)) {
            return $project;
        }
        return Project::find($destProjectId);
    }
}