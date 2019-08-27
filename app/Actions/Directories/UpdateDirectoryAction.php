<?php

namespace App\Actions\Directories;

use App\Models\File;

class UpdateDirectoryAction
{
    public function __invoke($data, $directoryId)
    {
        return tap(File::findOrFail($directoryId))->update($data)->fresh();
    }
}
