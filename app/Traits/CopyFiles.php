<?php

namespace App\Traits;

use App\Models\File;
use Ramsey\Uuid\Uuid;
use function blank;

trait CopyFiles
{
    private function importFileIntoDir(File $dir, File $file)
    {
        if (!$file->current) {
            return;
        }
        $usesUuid = blank($file->uses_uuid) ? $file->uuid : $file->uses_uuid;
        $f = $file->replicate()->fill([
            'uuid'         => Uuid::uuid4()->toString(),
            'directory_id' => $dir->id,
            'uses_uuid'    => $usesUuid,
            'owner_id'     => $dir->owner_id,
            'current'      => true,
            'project_id'   => $dir->project_id,
            'dataset_id'   => null,
        ]);

        $f->save();
    }

    private function isFileEntry(File $file): bool
    {
        return $file->mime_type !== 'directory';
    }
}
