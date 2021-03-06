<?php

namespace App\Actions\Files;

use Illuminate\Support\Facades\Storage;

trait SaveFile
{
    public function saveFile($file, $uuid)
    {
        $dir = $this->dirPathFromUuid($uuid);
        Storage::disk('mcfs')->putFileAs($dir, $file, $uuid);
        $fpath = Storage::disk('mcfs')->path("{$dir}/{$uuid}");
        chmod($fpath, 0777);
    }

    private function dirPathFromUuid($uuid)
    {
        $entries = explode('-', $uuid);
        $entry1 = $entries[1];

        return "{$entry1[0]}{$entry1[1]}/{$entry1[2]}{$entry1[3]}";
    }
}
