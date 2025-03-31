<?php

namespace App\Actions\Directories;

use App\Models\File;
use App\Models\User;
use App\Services\AuthService;
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
    public function __invoke($directoryId, $toDirectoryId, ?User $user = null)
    {
        $toDirectory = File::findOrFail($toDirectoryId);
        $destinationProjectId = $toDirectory->project_id;

        $directory = File::findOrFail($directoryId);
        $originalProjectId = $directory->project_id;

        if (is_null($user)) {
            $user = auth()->user();
        }

        if ($originalProjectId !== $destinationProjectId) {
            // Moving to another project. Ensure that we have access to that project.
            if (!AuthService::userCanAccessProjectId($user, $destinationProjectId)) {
                return null;
            }
        }

        // Setup variables for substr_replace to replace the old directory with the new path.
        $replaceWith = "{$toDirectory->path}/{$directory->name}";
        $oldPathLen = strlen($directory->path);

        $directoriesToUpdate = $this->getDirectoriesToUpdate($directory, $replaceWith, 0, $oldPathLen);

        // Batch update child directories and the directory that was originally moved. This needs to be in
        // a transaction as partial updates will leave us in a corrupted state.
        DB::transaction(function () use ($directoriesToUpdate, $directory, $toDirectory) {
            // Batch update all subdirs
            \Batch::update($directory, $directoriesToUpdate, 'id');

            // Update $directory
            $directory->update([
                'directory_id' => $toDirectory->id,
                'path'         => "{$toDirectory->path}/{$directory->name}",
                'project_id'   => $toDirectory->project_id,
            ]);
        });

        if ($originalProjectId !== $destinationProjectId) {
            // Update project_id for files in each subdirectory if move is to a different project
            foreach ($directoriesToUpdate as $directoryToUpdate) {
                DB::transaction(function () use ($directoryToUpdate, $destinationProjectId) {
                    File::where('directory_id', $directoryToUpdate->id)
                        ->where('mime_type', '<>', 'directory')
                        ->whereNull('deleted_at')
                        ->whereNull('dataset_id')
                        ->update(['project_id' => $destinationProjectId]);
                });
            }

            // Finally update the original moved directory's files.
            DB::transaction(function () use ($directory, $destinationProjectId) {
                File::where('directory_id', $directory->id)
                    ->where('mime_type', '<>', 'directory')
                    ->whereNull('deleted_at')
                    ->whereNull('dataset_id')
                    ->update(['project_id' => $destinationProjectId]);
            });
        }

        return $directory->fresh();
    }
}
