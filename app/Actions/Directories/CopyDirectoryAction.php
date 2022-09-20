<?php

namespace App\Actions\Directories;

use App\Models\File;
use App\Models\User;

class CopyDirectoryAction
{
    public function execute(File $dirToCopy, File $toDir, User $user, $newName): bool
    {
        return false;
    }
}