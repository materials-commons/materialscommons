<?php

namespace App\Traits\Files;

use App\Models\File;

trait FileHealth
{
    // setFileHealthMissing updates the health of a file to missing and sets the file_missing_at and file_missing_determined_by
    // fields. It also looks for any files that are pointed at and updates their health status as well.
    private function setFileHealthMissing(File $file, $determinedBy, $source = null): void
    {
        $source = blank($source) ? $file->upload_source : $source;
        $file->update([
            'health'                     => 'missing',
            'file_missing_at'            => now(),
            'file_missing_determined_by' => $determinedBy,
            'health_fixed_at'            => null,
            'health_fixed_by'            => null,
            'upload_source'              => $source,
        ]);

        $uuidToUse = $file->getFileUuidToUse();
        File::where('uses_uuid', $uuidToUse)->update([
            'health'                     => 'missing',
            'file_missing_at'            => now(),
            'file_missing_determined_by' => $determinedBy,
            'health_fixed_at'            => null,
            'health_fixed_by'            => null,
        ]);
    }

    // setFileHealthFixed updates the health of a file to fixed and sets the health_fixed_at and health_fixed_by fields.
    // It also looks for any files that are pointed at and updates their health status to fixed as well.
    private function setFileHealthFixed($file, $fixedBy, $source = null): void
    {
        $source = blank($source) ? $file->upload_source : $source;
        $file->update([
            'health_fixed_at'            => now(),
            'health_fixed_by'            => $fixedBy,
            'file_missing_at'            => null,
            'file_missing_determined_by' => null,
            'health'                     => 'fixed',
            'upload_source'              => $source,
        ]);

        $uuidToUse = $file->getFileUuidToUse();
        File::where('uses_uuid', $uuidToUse)->update([
            'health'                     => 'fixed',
            'health_fixed_at'            => now(),
            'health_fixed_by'            => $fixedBy,
            'file_missing_at'            => null,
            'file_missing_determined_by' => null,
        ]);
    }
}
