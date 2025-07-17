<?php

namespace App\Actions\Directories;

use App\Models\File;
use App\Models\User;
use Illuminate\Support\Collection;
use function substr_replace;

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
                                           int $oldPathLen, ?User $user = null): iterable
    {
        $directoriesToUpdate = $this->recursivelyRetrieveAllSubdirs($directory->id);

        // return list of sub directories with the new paths
        return $directoriesToUpdate->transform(function ($item) use ($newPath, $oldPathLen, $startingFrom, $user) {
            if (is_null($user)) {
                return [
                    'id'   => $item->id,
                    'path' => substr_replace($item->path, $newPath, $startingFrom, $oldPathLen),
                ];
            } else {
                return [
                    'id'       => $item->id,
                    'path'     => substr_replace($item->path, $newPath, $startingFrom, $oldPathLen),
                    'owner_id' => $user->id,
                ];
            }
        })->toArray();
    }

    /**
     * recursivelyRetrieveAllSubdirs returns a collection of the recursively derived list of sub directories for $directoryId
     *
     * @param $directoryId
     *
     * @return \Illuminate\Support\Collection
     */
    public function recursivelyRetrieveAllSubdirs($directoryId, $datasetId = null): Collection
    {
        // Here we need to recursively retrieve all directories underneath a root directory.
        // We start with a count of the current collection of $directoriesToReturn and merge into it
        // the current set of directory. If $count is unchanged after the merge then we know that there
        // were no more child directories and can break out of the loop. Otherwise, add those directories
        // into the list of directories to update and then get the query set of directories children.

        $directoriesToReturn = collect(); // Initial empty collection of subdirs to update
        $count = $directoriesToReturn->count(); // Initial count

        $query = File::where('directory_id', $directoryId)
                     ->where('current', true)
                     ->where('mime_type', 'directory')
                     ->whereNotNull('path')
                     ->whereNull('deleted_at');
        if (is_null($datasetId)) {
            $query = $query->whereNull('dataset_id');
        } else {
            $query = $query->where('dataset_id', $datasetId);
        }

        $dirs = $query->get(); // Get children of directory

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

            $query = File::whereIn('directory_id', $dirIds)
                         ->where('current', true)
                         ->whereNull('deleted_at')
                         ->where('mime_type', 'directory')
                         ->whereNotNull('path');

            if (is_null($datasetId)) {
                $query = $query->whereNull('dataset_id');
            } else {
                $query = $query->where('dataset_id', $datasetId);
            }

            $dirs = $query->get();
        }

        return $directoriesToReturn;
    }
}
