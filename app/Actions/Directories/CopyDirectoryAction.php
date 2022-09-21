<?php

namespace App\Actions\Directories;

use App\Models\File;
use App\Models\User;

class CopyDirectoryAction
{
    use ChildDirs;

    public function execute(File $dirToCopy, File $toDir, User $user, $newName): bool
    {
        return false;
    }

    private function copyToDir(File $dirToCopy, File $toDir, User $user, $newName): bool
    {
        $dirs = $this->recursivelyRetrieveAllSubdirs($dirToCopy);
        foreach ($dirs as $dir) {
            $newDir = $this->createDir($dir, $toDir);
            if (!$this->copyAllFilesInDir($dir, $newDir, $user, $newName)) {
                return false;
            }
        }
        return true;
    }

    private function copyAllFilesInDir(File $fromDir, File $toDir, User $user, $newName): bool
    {
        return false;
    }

    private function createDir(File $dir, File $toDir): File
    {
        return $dir;
    }
}