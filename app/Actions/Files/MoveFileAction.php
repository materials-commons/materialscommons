<?php

namespace App\Actions\Files;

use App\Models\File;
use Illuminate\Support\Facades\DB;

class MoveFileAction
{
    /**
     * @param  \App\Models\File  $file
     * @param $toDirectoryId
     * @return \App\Models\File|null
     */
    public function __invoke(File $file, $toDirectoryId)
    {
        // When moving a file we also have to move all of it's previous versions
        $previousVersions = $file->previousVersions()->get();
        DB::transaction(function () use ($file, $previousVersions, $toDirectoryId) {
            $file->update(['directory_id' => $toDirectoryId]);
            if ($previousVersions->isNotEmpty()) {
                File::whereIn('id', $previousVersions->pluck('id'))->update(['directory_id' => $toDirectoryId]);
            }
            $files = File::where('name', $file->name)->where('directory_id', $toDirectoryId)->get();
            foreach ($files as $f) {
                if ($file->id != $f->id) {
                    $f->update(['current' => false]);
                }
            }
        });

        return $file;
    }
}
