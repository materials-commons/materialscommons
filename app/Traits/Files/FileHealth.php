<?php

namespace App\Traits\Files;

trait FileHealth
{
    private function setFileHealthMissing($file, $determinedBy, $source): void
    {
        $file->update([
            'health'                     => 'missing',
            'file_missing_at'            => now(),
            'file_missing_determined_by' => $determinedBy,
            'health_fixed_at'            => null,
            'health_fixed_by'            => null,
            'upload_source'              => $source,
        ]);
    }

    private function setFileHealthFixed($file, $fixedBy, $source): void
    {
        $file->update([
            'health_fixed_at'            => now(),
            'health_fixed_by'            => $fixedBy,
            'file_missing_at'            => null,
            'file_missing_determined_by' => null,
            'health'                     => 'fixed',
            'upload_source'              => $source,
        ]);
    }
}
