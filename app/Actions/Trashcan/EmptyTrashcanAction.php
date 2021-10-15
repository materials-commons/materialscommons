<?php

namespace App\Actions\Trashcan;

use App\Models\File;
use Illuminate\Support\Carbon;

class EmptyTrashcanAction
{
    public function execute($projectId)
    {
        // Delete by setting the deleted_at past the date where they get deleted.
        $old = Carbon::now()->subDays(config('trash.expires_in_days') + 1);
        File::whereNotNull('deleted_at')
            ->where('project_id', $projectId)
            ->update(['deleted_at' => $old]);
    }
}