<?php

namespace App\Traits\Folders;

use Illuminate\Support\Facades\Storage;
use function array_key_exists;
use function chmod;

trait CreateFolder
{

    private $knownDirectories = [];

    private function createDirOnDiskIfNotExists($dirPath)
    {
        if (array_key_exists($dirPath, $this->knownDirectories)) {
            return;
        }

        $fullPath = Storage::disk('mcfs')->path($dirPath);
        if (Storage::disk('mcfs')->exists($dirPath)) {
            $this->knownDirectories[$dirPath] = true;
            return;
        }

        Storage::disk('mcfs')->makeDirectory($dirPath);
        $p = Storage::disk('mcfs')->path($dirPath);
        chmod($p, 0777);
        $this->knownDirectories[$dirPath] = true;
    }

}