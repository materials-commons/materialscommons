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
     * @return mixed
     */
    public function __invoke($directoryId, $toDirectoryId)
    {
        $toDirectory = File::findOrFail($toDirectoryId);
        $directory = File::findOrFail($directoryId);

        $directoriesToUpdate = File::where('directory_id', $directoryId)->whereNotNull('path')->get();

        // Only directories have a path. Get a list of all child directories of
        $directoriesToUpdate = $directoriesToUpdate->transform(function ($item, $key) use ($toDirectory, $directory) {
            return [
                'id' => $item->id,
                'path' => "{$toDirectory->path}/{$directory->name}/{$item->name}"
            ];
        })->toArray();

        DB::transaction(function () use ($directoriesToUpdate, $directory, $toDirectory) {
            \Batch::update($directory, $directoriesToUpdate, 'id');
            $directory->update([
                'directory_id' => $toDirectory->id, 'path' => "{$toDirectory->path}/{$directory->name}"
            ]);
        });
        return $directory->fresh();
    }
}
