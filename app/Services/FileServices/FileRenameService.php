<?php

namespace App\Services\FileServices;

use App\Models\File;
use Illuminate\Support\Facades\DB;

class FileRenameService
{
    public function rename(File $file, string $name, ?string $url = null): File
    {
        $previousVersions = $file->previousVersions()->get();
        DB::transaction(function () use ($file, $previousVersions, $name, $url) {
            if (!is_null($url)) {
                $file->update(['name' => $name, 'url' => $url]);
            } else {
                $file->update(['name' => $name]);
            }
            if ($previousVersions->isNotEmpty()) {
                File::whereIn('id', $previousVersions->pluck('id')->toArray())->update(['name' => $name]);
            }
        });

        return $file;
    }
}
