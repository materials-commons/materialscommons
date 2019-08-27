<?php

namespace App\Actions\Directories;

use App\Models\File;

class DeleteDirectoryAction
{
    /**
     * Deletes a directory only if it is empty and is not the root directory.
     *
     * @param  File  $directory
     * @throws \Exception
     */
    public function __invoke(File $directory)
    {
        // For now only delete directories that are empty
        abort_unless($directory->name != '/', 400, 'Cannot delete project root directory');
        $count = File::where('directory_id', $directory->id)->count();
        abort_unless($count == 0, 400, 'Directory not empty');
        $directory->delete();
    }
}
