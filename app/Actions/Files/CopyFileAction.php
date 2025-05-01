<?php

namespace App\Actions\Files;

use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CopyFileAction
{
    // Copy a file to another directory. Can also move between projects. It assumes that all access checks
    //    // have already been done.
    public function execute(File $fileToCopy, File $toDir, User $user, $newName = null): bool
    {
        return $this->copyToDir($fileToCopy, $toDir, $user, $newName);
    }

    private function copyToDir(File $fileToCopy, File $toDir, User $user, $newName): bool
    {
        $nameToUse = is_null($newName) ? $fileToCopy->name : $newName;
        $this->markFilesWithSameNameInDirAsInactive($nameToUse, $toDir);

        $fileToCopy->replicate(["unique_proj_dir"])
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
