<?php

namespace App\Actions\Files;

use App\Models\File;
use App\Services\AuthService;
use Illuminate\Support\Facades\DB;

class MoveFileAction
{
    public function __invoke(File $file, $toDirectoryId, $userId = null)
    {
        // When moving a file we also have to move all of its previous versions, so get the list of
        // previous versions in the originating directory.

        if (is_null($userId)) {
            $userId = auth()->user()->id;
        }

        $toDir = File::find($toDirectoryId);

        if (is_null($toDir)) {
            return null;
        }

        if ($toDir->project_id != $file->project_id) {
            if (!AuthService::userIdCanAccessProjectId($userId, $toDir->project_id)) {
                return null;
            }
        }

        $originalDirectoryId = $file->directory_id;

        DB::transaction(function () use ($file, $originalDirectoryId, $toDir, $userId) {
            // Step 1: Mark any files currently in directory that have the same name as inactive
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
                'owner_id'     => $userId,
            ]);

            // Step 3: Move over previous versions of the file from original directory to the new directory. Note
            // that we don't need to check current flag, because in step 2 we already moved the file we want to
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
                  'owner_id'     => $userId,
              ]);
        });

        return $file;
    }
}
