<?php

namespace App\Actions\Files;

use App\Models\File;
use App\Traits\PathFromUUID;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteFileAction
{
    use PathFromUUID;

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

        Storage::disk('local')->delete($this->filePathFromUuid($file->uuid));
        $previousVersions->each(function ($file) {
            Storage::disk('local')->delete($this->filePathFromUuid($file->uuid));
        });
    }
}