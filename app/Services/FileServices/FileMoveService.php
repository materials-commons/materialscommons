<?php

namespace App\Services\FileServices;

use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FileMoveService
{
    public function move(File $file, File $toDir, User $user): File
    {
        $originalDirectoryId = $file->directory_id;

        DB::transaction(function () use ($file, $originalDirectoryId, $toDir, $user) {
            // Step 1: Deactivate any files in target dir with same name
            File::where('name', $file->name)
                ->where('directory_id', $toDir->id)
                ->whereNull('dataset_id')
                ->whereNull('deleted_at')
                ->update(['current' => false]);

            // Step 2: Move target file to directory and set as current
            $file->update([
                'directory_id' => $toDir->id,
                'project_id'   => $toDir->project_id,
                'current'      => true,
                'owner_id'     => $user->id,
            ]);

            // Step 3: Move previous versions from original directory
            DB::table('files')
                ->where('directory_id', $originalDirectoryId)
                ->whereNull('dataset_id')
                ->whereNull('deleted_at')
                ->where('name', $file->name)
                ->update([
                    'directory_id' => $toDir->id,
                    'project_id'   => $toDir->project_id,
                    'current'      => false,
                    'owner_id'     => $user->id,
                ]);
        });

        return $file;
    }
}
