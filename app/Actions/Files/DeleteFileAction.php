<?php

namespace App\Actions\Files;

use App\Models\File;
use App\Traits\PathForFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteFileAction
{
    use PathForFile;

    /**
     * Delete a file and all its previous versions
     *
     * @param  \App\Models\File  $file
     */
    public function __invoke(File $file)
    {
        $previousVersions = $file->previousVersions()->get();
        // First delete the file and its previous versions, and then delete the physical
        // files on disk.
        DB::transaction(function () use ($file, $previousVersions) {
            $file->delete();
            if ($previousVersions->isNotEmpty()) {
                File::whereIn('id', $previousVersions->pluck('id'))->delete();
            }
        });

        // Need to make sure nothing points at this file
        if ( ! $this->filesPointAtFile($file)) {
            Storage::disk('local')->delete($this->getFilePathForFile($file));
        }
        $previousVersions->each(function ($file) {
            if ( ! $this->filesPointAtFile($file)) {
                Storage::disk('local')->delete($this->getFilePathForFile($file));
            }
        });
    }

    private function filesPointAtFile(File $file)
    {
        if (isset($file->uses_uuid)) {
            return false;
        }

        $count = File::where('uses_uuid', $file->uuid)->count();

        return $count != 0;
    }
}