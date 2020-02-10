<?php

namespace App\Traits;

use App\Models\File;

trait PathForFile
{
    public function getDirPathForFile(File $file)
    {
        $uuid = $this->getUuid($file);

        return $this->getDirPathForFileFromUuid($uuid);
    }

    public function getFilePathForFile(File $file)
    {
        $uuid = $this->getUuid($file);

        return $this->getDirPathForFileFromUuid($uuid).'/'.$uuid;
    }

    private function getDirPathForFileFromUuid($uuid)
    {
        $entries = explode('-', $uuid);
        $entry1 = $entries[1];

        return "{$entry1[0]}{$entry1[1]}/{$entry1[2]}{$entry1[3]}";
    }

    public function getUuid(File $file)
    {
        if (isset($file->uses_uuid)) {
            if ($file->uses_uuid != "") {
                return $file->uses_uuid;
            }
        }

        return $file->uuid;
    }
}

