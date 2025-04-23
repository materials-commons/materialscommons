<?php

namespace App\Actions\Files;

use App\Models\File;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Support\Facades\DB;

class CopyFileAction
{
    // Copy a file to another directory. If that directory is a different project, then checks
    // if the user has access to that project. File can be given a different name when it is copied.
    // The copy will fail if user doesn't have permission, if a file (or directory) with the same
    // name already exists in the directory the file is being copied to. Note that you can make
    // a copy of the file in the same directory, you just need to give it a new name (that doesn't
    // exist in the directory).
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

        if ($this->fileWithSameNameExistsInDir($nameToUse, $toDir)) {
            return false;
        }

        return $fileToCopy->replicate(["unique_proj_dir"])
                          ->fill([
                              'uuid'         => uuid(),
                              'name'         => $nameToUse,
                              'owner_id'     => $user->id,
                              'project_id'   => $toDir->project_id,
                              'directory_id' => $toDir->id,
                              'uses_uuid'    => $fileToCopy->getFileUuidToUse(),
                          ])
                          ->save();
    }

    private function fileWithSameNameExistsInDir($nameToUse, File $toDir): bool
    {
        $count = DB::table("files")
                   ->where("directory_id", $toDir->id)
                   ->whereNull('dataset_id')
                   ->whereNull('deleted_at')
                   ->where("name", $nameToUse)
                   ->count();
        if ($count == 0) {
            // no matching file names
            return false;
        }

        return true;
    }
}