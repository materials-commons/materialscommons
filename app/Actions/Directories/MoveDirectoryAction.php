<?php

namespace App\Actions\Directories;

use App\Models\File;
use Illuminate\Support\Facades\DB;

class MoveDirectoryAction
{
    use ChildDirs;

    /**
     * Move $directoryId to directory $toDirectoryId and update all subdirs.
     *
     * @param $directoryId
     * @param $toDirectoryId
     *
     * @return mixed
     */
    public function __invoke($directoryId, $toDirectoryId)
    {
        $toDirectory = File::findOrFail($toDirectoryId);
        $directory   = File::findOrFail($directoryId);

        // Setup variables for substr_replace to replace the old directory with the new path.
        $replaceWith = "{$toDirectory->path}/{$directory->name}";
        $oldPathLen  = strlen($directory->path);

        $directoriesToUpdate = $this->getDirectoriesToUpdate($directory, $replaceWith, 0, $oldPathLen);

        // Batch update child directories and the directory that was originally moved. This needs to be in
        // a transaction as partial updates will leave us in a corrupted state.
        DB::transaction(function () use ($directoriesToUpdate, $directory, $toDirectory) {
            \Batch::update($directory, $directoriesToUpdate, 'id');
            $directory->update([
                'directory_id' => $toDirectory->id, 'path' => "{$toDirectory->path}/{$directory->name}",
            ]);
        });

        return $directory->fresh();
    }
}
