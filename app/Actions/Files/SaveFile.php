<?php

namespace App\Actions\Files;

use App\Jobs\Files\ConvertFileJob;
use App\Jobs\Files\GenerateThumbnailJob;
use App\Models\File;
use App\Models\Script;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use function chmod;

trait SaveFile
{
    public function saveFile($file, $uuid): bool
    {
        try {
            $dir = $this->dirPathFromUuid($uuid);
            $savedStatus = Storage::disk('mcfs')->putFileAs($dir, $file, $uuid);
            $fpath = Storage::disk('mcfs')->path("{$dir}/{$uuid}");
            chmod($fpath, 0777);
            return $savedStatus;
        } catch (\Exception $e) {
            Log::error("Error in saveFile {$file->id}: {$e->getMessage()}");
            return false;
        }
    }

    public function saveFileContents(File $file, $contents): bool
    {
        try {
            Storage::disk('mcfs')->makeDirectory($file->pathDirPartial());
            $savedStatus = Storage::disk('mcfs')->put($file->realPathPartial(), $contents);
            $fpath = Storage::disk('mcfs')->path($file->realPathPartial());
            chmod($fpath, 0777);
            return $savedStatus;
        } catch (\Exception $e) {
            Log::error("Error in saveFileContents for file {$file->id}: {$e->getMessage()}");
            return false;
        }
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
