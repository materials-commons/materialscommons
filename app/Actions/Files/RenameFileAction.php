<?php

namespace App\Actions\Files;

use App\Models\File;
use Illuminate\Support\Facades\DB;

class RenameFileAction
{
    /**
     * Rename a file and all its previous version
     *
     * @param  \App\Models\File  $file
     * @param $name
     * @return \App\Models\File|null
     */
    public function __invoke(File $file, $name)
    {
        // When renaming a file make sure to rename its previous versions
        $previousVersions = $file->previousVersions()->get();
        DB::transaction(
            function () use ($file, $previousVersions, $name) {
                $file->update(['name' => $name]);
                if ($previousVersions->isNotEmpty()) {
                    File::whereIn('id', $previousVersions->pluck('id'))->update(['name' => $name]);
                }
            }
        );

        return $file->fresh();
    }
}
