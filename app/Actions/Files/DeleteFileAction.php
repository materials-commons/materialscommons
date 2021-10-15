<?php

namespace App\Actions\Files;

use App\Models\File;
use App\Traits\PathForFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DeleteFileAction
{
    use PathForFile;

    /**
     * Delete a file and all its previous versions
     */
    public function __invoke(File $file)
    {
        $previousVersions = $file->previousVersions()->get();
        // First delete the file and its previous versions, and then delete the physical
        // files on disk.
        DB::transaction(function () use ($file, $previousVersions) {
            $now = Carbon::now();
            $file->update(['deleted_at' => $now]);
            if ($previousVersions->isNotEmpty()) {
                File::whereIn('id', $previousVersions->pluck('id'))->update(['deleted_at' => $now]);
            }
        });
    }
}
