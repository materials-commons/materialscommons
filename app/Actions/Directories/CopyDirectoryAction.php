<?php

namespace App\Actions\Directories;

use App\Helpers\PathHelpers;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use App\Services\AuthService;
use App\Traits\CopyFiles;
use Illuminate\Support\Facades\DB;

class CopyDirectoryAction
{
    use ChildDirs;
    use CopyFiles;

    private array $dirsByPath = [];
    private Project $project;

    public function execute(File $dirToCopy, File $toDir, User $user): bool
    {
        $this->project = Project::findOrFail($toDir->project_id);
        if ($dirToCopy->project_id != $toDir->project_id) {
            return $this->copyToDifferentProject($dirToCopy, $toDir, $user);
        }
        return $this->copyToDir($dirToCopy, $toDir, $user);
    }

    private function copyToDifferentProject(File $dirToCopy, File $toDir, User $user): bool
    {
        if (!AuthService::userCanAccessProjectId($user, $toDir->project_id)) {
            return false;
        }
        return $this->copyToDir($dirToCopy, $toDir, $user);
    }

    private function copyToDir(File $dirToCopy, File $toDir, User $user): bool
    {
        if ($this->fileOrDirWithSameNameExistsInDir($dirToCopy, $toDir)) {
            return false;
        }

        $dirs = $this->recursivelyRetrieveAllSubdirs($dirToCopy->id);

        // Prepend the $dirToCopy to make sure all files get copied out of it.
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
        $cursor = File::query()
                      ->where('directory_id', $fromDir->id)
                      ->where('mime_type', '<>', 'directory')
                      ->where('current', true)
                      ->whereNull('deleted_at')
                      ->whereNull('dataset_id')
                      ->cursor();
        foreach ($cursor as $file) {
            $this->importFileIntoDir($toDir, $file);
        }
        return true;
    }

    private function fileOrDirWithSameNameExistsInDir($dirBeingCopied, File $toDir): bool
    {
        $count = DB::table("files")
                   ->where("directory_id", $toDir->id)
                   ->whereNull('dataset_id')
                   ->whereNull('deleted_at')
                   ->where("name", $dirBeingCopied->name)
                   ->count();
        if ($count == 0) {
            // no matching file names
            return false;
        }

        return true;
    }
}