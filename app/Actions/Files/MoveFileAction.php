<?php

namespace App\Actions\Files;

use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MoveFileAction
{
    // Move a file to another directory. Can also move between projects. It assumes that all access checks
    // have already been done.
    public function __invoke(File $file, File $toDir, User $user)
    {
        $originalDirectoryId = $file->directory_id;

        DB::transaction(function () use ($file, $originalDirectoryId, $toDir, $user) {
            // Step 1: Mark any files currently in the directory that have the same name as inactive
            File::where('name', $file->name)
                ->where('directory_id', $toDir->id)
                ->whereNull('dataset_id')
                ->whereNull('deleted_at')
                ->update(['current' => false]);

            // Step 2: Move the file by changing its directory and making sure it's the current file
            $file->update([
                'directory_id' => $toDir->id,
                'project_id'   => $toDir->project_id,
                'current'      => true,
                'owner_id' => $user->id,
            ]);

            // Step 3: Move over previous versions of the file from the original directory to the new directory. Note
            // that we don't need to check the current flag, because in step 2 we already moved the file we want to
            // move. We set the current flag for all these files to false in case the user moved a previous version.
            DB::table("files")
              ->where("directory_id", $originalDirectoryId)
              ->whereNull('dataset_id')
              ->whereNull('deleted_at')
              ->where("name", $file->name)
              ->update([
                  'directory_id' => $toDir->id,
                  'project_id'   => $toDir->project_id,
                  'current'      => false,
                  'owner_id' => $user->id,
              ]);
        });

        return $file;
    }
}
