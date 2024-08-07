<?php

namespace App\Actions\Files;

use App\Models\File;
use Illuminate\Support\Facades\Storage;
use function chmod;

trait SaveFile
{
    public function saveFile($file, $uuid)
    {
        $dir = $this->dirPathFromUuid($uuid);
        Storage::disk('mcfs')->putFileAs($dir, $file, $uuid);
        $fpath = Storage::disk('mcfs')->path("{$dir}/{$uuid}");
        chmod($fpath, 0777);
    }

    public function saveFileContents(File $file, $contents): void
    {
        Storage::disk('mcfs')->makeDirectory($file->pathDirPartial());
        Storage::disk('mcfs')->put($file->realPathPartial(), $contents);
        $fpath = Storage::disk('mcfs')->path($file->realPathPartial());
        chmod($fpath, 0777);
    }

    private function matchingFileInDir($directoryId, $checksum, $name)
    {
        return File::where('checksum', $checksum)
                   ->where('directory_id', $directoryId)
                   ->whereNull('deleted_at')
                   ->whereNull('dataset_id')
                   ->where('name', $name)
                   ->first();
    }

    private function dirPathFromUuid($uuid)
    {
        $entries = explode('-', $uuid);
        $entry1 = $entries[1];

        return "{$entry1[0]}{$entry1[1]}/{$entry1[2]}{$entry1[3]}";
    }
}
