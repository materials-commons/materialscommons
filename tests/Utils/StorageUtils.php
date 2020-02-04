<?php

namespace Tests\Utils;

use Illuminate\Support\Facades\Storage;

class StorageUtils
{
    public static function clearStorage()
    {
        foreach (Storage::directories() as $dir) {
            if ($dir == "public") {
                continue;
            }

            Storage::deleteDirectory($dir);
        }
    }
}