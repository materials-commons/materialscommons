<?php

namespace App\Actions\Directories;

use App\Models\File;

trait ChildDirs
{
    /**
     * getDirectoriesToUpdate returns a list of all recursive subdirectories with updated paths that
     * can be used in a batch update.
     *
     * @param  \App\Models\File  $directory  The directory to start from
     * @param  string  $newPath  Path to replace with
     * @param  int  $startingFrom  starting position
     * @param  int  $oldPathLen  old path length
     *
     * @return array
     */
    public function getDirectoriesToUpdate(File $directory, string $newPath, int $startingFrom, int $oldPathLen): iterable
    {
        $directoriesToUpdate = $this->recursivelyRetrieveAllSubdirs($directory->id);

        // return list of sub directories with the new paths
        return $directoriesToUpdate->transform(function ($item) use ($newPath, $oldPathLen, $startingFrom) {
            return [
                'id'   => $item->id,
                'path' => substr_replace($item->path, $newPath, $startingFrom, $oldPathLen),
            ];
        })->toArray();
    }

    /**
     * recursivelyRetrieveAllSubdirs returns a collection of the recursively derived list of sub directories for $directoryId
     *
     * @param $directoryId
     *
     * @return \Illuminate\Support\Collection
     */
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