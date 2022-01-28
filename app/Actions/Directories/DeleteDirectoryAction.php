<?php

namespace App\Actions\Directories;

use App\Models\File;
use Illuminate\Support\Carbon;

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
        abort_if($directory->name == '/', 400, 'Cannot delete project root directory');
        $directory->update(['deleted_at' => Carbon::now()]);
    }
}
