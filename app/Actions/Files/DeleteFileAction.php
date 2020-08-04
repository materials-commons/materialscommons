<?php

namespace App\Actions\Files;

use App\Models\File;
use App\Traits\PathForFile;
use Illuminate\Support\Facades\DB;

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
        // ** For now don't delete. There is a possible race condition here. **
        // ** Uncomment these lines once there is a solution in place for handling this. **

//        if ( ! $this->filesPointAtFile($file)) {
//            Storage::disk('mcfs')->delete($this->getFilePathForFile($file));
//        }
//        $previousVersions->each(function ($file) {
//            if ( ! $this->filesPointAtFile($file)) {
//                Storage::disk('mcfs')->delete($this->getFilePathForFile($file));
//            }
//        });
    }

    private function filesPointAtFile(File $file)
    {
        if (!blank($file->uses_uuid)) {
            // This file has a uses_uuid so nothing points at it as its not a file
            // with an underlying physical file. Instead it points at something itself.
            return false;
        }

        // Count how many files have their uses_uuid set to this files uuid.
        $count = File::where('uses_uuid', $file->uuid)->count();

        return $count != 0;
    }
}
