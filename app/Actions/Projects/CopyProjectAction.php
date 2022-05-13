<?php

namespace App\Actions\Projects;

use App\Models\Project;
use App\Traits\CopyFiles;
use App\Traits\GetProjectFiles;

class CopyProjectAction
{
    use GetProjectFiles;
    use CopyFiles;

    public function copyProject(Project $fromProject, Project $toProject)
    {
        $toProject->load(['rootDir']);

        foreach($this->getFilesCursorForProject($fromProject->id) as $file) {
            if ($this->isFileEntry($file)) {
                $dir = $this->getDirectoryOrCreateIfDoesNotExist($toProject->rootDir, $file->directory->path, $toProject);
                $this->importFileIntoDir($dir, $file);
            }
        }
    }
}