<?php

namespace App\Actions\Projects;

use App\Models\Project;
use App\Traits\CopyFiles;
use App\Traits\CreateDirectories;
use App\Traits\GetProjectFiles;

class CopyProjectAction
{
    use GetProjectFiles;
    use CopyFiles;
    use CreateDirectories;

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