<?php

namespace App\Actions\Directories;

use App\Models\File;
use Illuminate\Support\Facades\DB;

class MoveDirectoryAction
{
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

        $directoriesToUpdate = $this->recursivelyRetrieveAllSubdirs($directoryId);

        // We now have a complete list of directories to update. The update will be done in a batch operation.
        // Go through the list of directories and transform to new path.

        // Setup variables for substr_replace to replace the old directory with the new path.
        $replaceWith = "{$toDirectory->path}/{$directory->name}";
        $oldPathLen  = strlen($directory->path);

        $directoriesToUpdate = $directoriesToUpdate->transform(function ($item) use ($replaceWith, $oldPathLen) {
            return [
                'id'   => $item->id,
                'path' => substr_replace($item->path, $replaceWith, 0, $oldPathLen),
            ];
        })->toArray();

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

    private function recursivelyRetrieveAllSubdirs($directoryId)
    {
        // Here we need to recursively update the path of all directories underneath the moved directory.
        // We start with a count of the current collection of $directoriesToUpdate and merge into it
        // the current set of directory. If $count is unchanged after the merge then we know that there
        // were no more child directories and can break out of the loop. Otherwise, add those directories
        // into the list of directories to update and then get the query set of directories children.

        $directoriesToUpdate = collect(); // Initial empty collection of subdirs to update
        $count               = $directoriesToUpdate->count(); // Initial count

        $dirs = File::where('directory_id', $directoryId)->whereNotNull('path')->get(); // Get children of directory being moved
        while (true) {
            $directoriesToUpdate      = $directoriesToUpdate->merge($dirs);
            $directoriesToUpdateCount = $directoriesToUpdate->count();
            if ($count == $directoriesToUpdateCount) {
                // Count didn't change so no new directories were found so exit loop.
                break;
            }

            // If we are here then there were new directories added to $directoriesToUpdate.
            // Get their ids and query for their child dirs.
            $count = $directoriesToUpdateCount;

            $dirIds = $dirs->transform(function ($item) {
                return $item->id;
            })->toArray();

            $dirs = File::whereIn('directory_id', $dirIds)->whereNotNull('path')->get();
        }

        return $directoriesToUpdate;
    }
}
