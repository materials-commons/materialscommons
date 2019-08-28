<?php

namespace App\Actions\Directories;

use App\Models\File;
use Illuminate\Support\Facades\DB;

class RenameDirectoryAction
{
    use ChildDirs;

    public function __invoke($directoryId, $newDirName)
    {
        $directory = File::findOrFail($directoryId);
        $parentDir = File::findOrFail($directory->directory_id);

        $replaceWith = "{$parentDir->path}/{$newDirName}";
        $oldPathLen  = strlen($directory->path);

        $directoriesToUpdate = $this->getDirectoriesToUpdate($directory, $replaceWith, 0, $oldPathLen);

        // Batch update child directories and the directory that was originally moved. This needs to be in
        // a transaction as partial updates will leave us in a corrupted state.
        DB::transaction(function () use ($directoriesToUpdate, $directory, $replaceWith, $newDirName) {
            \Batch::update($directory, $directoriesToUpdate, 'id');
            $directory->update(['path' => $replaceWith, 'name' => $newDirName]);
        });

        return $directory->fresh();
    }
}
