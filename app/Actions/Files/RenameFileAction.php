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
    public function __invoke(File $file, $name, $url = null)
    {
        // When renaming a file make sure to rename its previous versions
        $previousVersions = $file->previousVersions()->get();
        DB::transaction(
            function () use ($file, $previousVersions, $name, $url) {
                if (!is_null($url)) {
                    $file->update(['name' => $name, 'url' => $url]);
                } else {
                    $file->update(['name' => $name]);
                }
                if ($previousVersions->isNotEmpty()) {
                    File::whereIn('id', $previousVersions->pluck('id')->toArray())->update(['name' => $name]);
                }
            }
        );

        return $file;
    }
}
