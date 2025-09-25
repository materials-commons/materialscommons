<?php

namespace App\Services\FileServices;

use App\Models\File;
use Illuminate\Support\Facades\DB;

class FileVersioningService
{
    public function setActive(File $file): File
    {
        if ($file->current) {
            return $file;
        }
        DB::transaction(function () use ($file) {
            File::where('directory_id', $file->directory_id)
                ->where('name', $file->name)
                ->whereNull('dataset_id')
                ->whereNull('deleted_at')
                ->update(['current' => false]);
            $file->update(['current' => true]);
        });
        return $file->fresh() ?: $file;
    }

    public function previousVersions(File $file)
    {
        return $file->previousVersions()->get();
    }
}
