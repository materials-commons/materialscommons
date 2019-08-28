<?php

namespace App\Actions\Directories;

use App\Helpers\PathHelpers;
use App\Models\File;
use Illuminate\Support\Facades\DB;

class RenameDirectoryAction
{
    use ChildDirs;

    public function __invoke($directoryId, $newDirName)
    {
        $directory = File::findOrFail($directoryId);
        $parentDir = File::findOrFail($directory->directory_id);

        // Normalize the directory name to use because the parentDir might be root
        // in which case its path is "/", and without normalization the replaceWith
        // path we build would start with '//'. For example if newDirName = mynewdir
        // and $parentDir->path == "/", then without normalization we end up with
        // //mynewdir. The call to normalizePath fixes this.
        $replaceWith = PathHelpers::normalizePath("{$parentDir->path}/{$newDirName}");
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
