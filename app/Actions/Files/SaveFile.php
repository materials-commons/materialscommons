<?php

namespace App\Actions\Files;

trait SaveFile
{
    public function saveFile($file, $uuid)
    {
        $dir = $this->dirPathFromUuid($uuid);
        $file->save("hello");
        //        $file->save($dir, $uuid);
    }

    private function dirPathFromUuid($uuid)
    {
        $entries = explode('-', $uuid);
        $entry1  = $entries[0];

        return "{$entry1[0]}{$entry1[1]}/{$entry1[2]}{$entry1[3]}";
    }
}
