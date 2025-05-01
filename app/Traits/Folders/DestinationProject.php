<?php

namespace App\Traits\Folders;

use App\Models\File;
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

    private function getDestinationDir(): ?File
    {
        $destDirId = request()->get('destdir');
        if (is_null($destDirId)) {
            return null;
        }
        return File::find($destDirId);
    }

    private function getDestinationDirId(): ?int
    {
        return request()->get('destdir');
    }
}