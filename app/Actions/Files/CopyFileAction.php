<?php

namespace App\Actions\Files;

use App\Models\File;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Support\Facades\DB;

class CopyFileAction
{
    // Copy a file to another directory. If that directory is in a different project, then checks
    // if the user has access to that project. File can be given a different name when it is copied.
    // The copy will fail if user doesn't have permission. If a file with the same name already
    // exists in the directory the file is being copied to then that file is marked as inactive.
    public function execute(File $fileToCopy, File $toDir, User $user, $newName = null): bool
    {
        if ($fileToCopy->project_id != $toDir->project_id) {
            return $this->copyToDifferentProject($fileToCopy, $toDir, $user, $newName);
        }

        return $this->copyToDir($fileToCopy, $toDir, $user, $newName);
    }

    // File is being copied to a directory in a different project. When this happens we need to
    private function copyToDifferentProject(File $fileToCopy, File $toDir, User $user, $newName): bool
    {
        if (!AuthService::userCanAccessProjectId($user, $toDir->project_id)) {
            return false;
        }

        return $this->copyToDir($fileToCopy, $toDir, $user, $newName);
    }

    private function copyToDir(File $fileToCopy, File $toDir, User $user, $newName): bool
    {
        // Check if name already exists in dir and fail if it does
        $nameToUse = is_null($newName) ? $fileToCopy->name : $newName;

        $this->markFilesWithSameNameInDirAsInactive($nameToUse, $toDir);

        $fileToCopy->replicate()
                   ->fill([
                       'uuid'         => uuid(),
                       'name'         => $nameToUse,
                       'owner_id'     => $user->id,
                       'project_id'   => $toDir->project_id,
                       'directory_id' => $toDir->id,
                       'current'      => true,
                       'uses_uuid'    => $fileToCopy->getFileUuidToUse(),
                   ])
                   ->save();
        return true;
    }

    private function markFilesWithSameNameInDirAsInactive($nameToUse, File $toDir): void
    {
        DB::table("files")
          ->where("directory_id", $toDir->id)
          ->whereNull('dataset_id')
          ->whereNull('deleted_at')
          ->where("name", $nameToUse)
          ->update(['current' => false]);
    }
}
