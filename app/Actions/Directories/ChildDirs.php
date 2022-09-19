<?php

namespace App\Actions\Directories;

use App\Models\File;
use Illuminate\Support\Collection;

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
    public function getDirectoriesToUpdate(File $directory, string $newPath, int $startingFrom,
        int $oldPathLen): iterable
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
    public function recursivelyRetrieveAllSubdirs($directoryId): Collection
    {
        // Here we need to recursively retrieve all directories underneath a root directory.
        // We start with a count of the current collection of $directoriesToReturn and merge into it
        // the current set of directory. If $count is unchanged after the merge then we know that there
        // were no more child directories and can break out of the loop. Otherwise, add those directories
        // into the list of directories to update and then get the query set of directories children.

        $directoriesToReturn = collect(); // Initial empty collection of subdirs to update
        $count = $directoriesToReturn->count(); // Initial count

        $dirs = File::where('directory_id', $directoryId)
                    ->whereNotNull('path')
                    ->whereNull('dataset_id')
                    ->whereNull('deleted_at')
                    ->where('current', true)
                    ->get(); // Get children of directory being moved

        while (true) {
            $directoriesToReturn = $directoriesToReturn->merge($dirs);
            $directoriesToReturnCount = $directoriesToReturn->count();
            if ($count == $directoriesToReturnCount) {
                // Count didn't change so no new directories were found so exit loop.
                break;
            }

            // If we are here then there were new directories added to $directoriesToReturn.
            // Get their ids and query for their child dirs.
            $count = $directoriesToReturnCount;

            $dirIds = $dirs->transform(function ($item) {
                return $item->id;
            })->toArray();

            $dirs = File::whereIn('directory_id', $dirIds)
                        ->where('current', true)
                        ->whereNull('dataset_id')
                        ->whereNull('deleted_at')
                        ->whereNotNull('path')
                        ->get();
        }

        return $directoriesToReturn;
    }
}
