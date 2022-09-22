<?php

namespace App\Actions\Directories;

use App\Helpers\PathHelpers;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use App\Traits\CopyFiles;

class CopyDirectoryAction
{
    use ChildDirs;
    use CopyFiles;

    private array $dirsByPath = [];
    private Project $project;

    public function execute(File $dirToCopy, File $toDir, User $user): bool
    {
        $this->project = Project::findOrFail($toDir->project_id);
        return $this->copyToDir($dirToCopy, $toDir, $user);
    }

    private function copyToDir(File $dirToCopy, File $toDir, User $user): bool
    {
        $dirs = $this->recursivelyRetrieveAllSubdirs($dirToCopy);

        // Now that we have all subdirectories, prepend $dirToCopy so that
        // we get the complete list of directories to copy files from
        $dirs->prepend($dirToCopy);
        foreach ($dirs as $dir) {
            $newDir = $this->getDirectoryOrCreateIfDoesNotExist($toDir, $dir->path, $this->project);
            if (!$this->copyAllFilesInDir($dir, $newDir, $user)) {
                return false;
            }
        }
        return true;
    }

    private function copyAllFilesInDir(File $fromDir, File $toDir, User $user): bool
    {
        echo "copyAllFilesInDir {$fromDir->path}, {$toDir->path}\n";
        $cursor = File::query()
                      ->where('directory_id', $fromDir)
                      ->where('mime_type', '<>', 'directory')
                      ->where('current', true)
                      ->whereNull('deleted_at')
                      ->whereNull('dataset_id')
                      ->cursor();
        foreach($cursor as $file) {
            echo "Copying: {$file->name}\n";
            $this->importFileIntoDir($toDir, $file);
        }
        return true;
    }
}