<?php

namespace App\Traits;

trait PathForFile
{
    public function getDirPathForFile($uuid)
    {
        $entries = explode('-', $uuid);
        $entry1 = $entries[1];

        return "{$entry1[0]}{$entry1[1]}/{$entry1[2]}{$entry1[3]}";
    }

    public function getFilePathForFile($uuid)
    {
        return $this->getDirPathForFile($uuid).'/'.$uuid;
    }
}

