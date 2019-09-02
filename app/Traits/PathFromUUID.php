<?php

namespace App\Traits;

trait PathFromUUID
{
    public function getDirPathFromUuid($uuid)
    {
        $entries = explode('-', $uuid);
        $entry1  = $entries[0];

        return "{$entry1[0]}{$entry1[1]}/{$entry1[2]}{$entry1[3]}";
    }

    public function filePathFromUuid($uuid)
    {
        return $this->getDirPathFromUuid($uuid).'/'.$uuid;
    }
}

