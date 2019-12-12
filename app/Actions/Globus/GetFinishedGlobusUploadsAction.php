<?php

namespace App\Actions\Globus;

use App\Models\GlobusUpload;

class GetFinishedGlobusUploadsAction
{
    // Returns the list of globus uploads that are not in process that **do not** have a project_id matching
    // an upload that is being processed.
    public function __invoke()
    {
        return GlobusUpload::where('uploading', false)
                           ->where('loading', false)
                           ->whereNotIn('project_id', function ($q) {
                               $q->select('project_id')->from('globus_uploads')
                                 ->where('loading', true);
                           })->get();
    }
}