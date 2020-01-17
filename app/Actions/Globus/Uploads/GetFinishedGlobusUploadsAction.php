<?php

namespace App\Actions\Globus\Uploads;

use App\Models\GlobusUploadDownload;

class GetFinishedGlobusUploadsAction
{
    // Returns the list of globus uploads that are not in process and that **do not** have a project_id matching
    // an upload that is being processed.
    public function __invoke()
    {
        return GlobusUploadDownload::where('uploading', false)
                                   ->where('loading', false)
                                   ->where('type', 'upload')
                                   ->whereNotIn('project_id', function ($q) {
                                       $q->select('project_id')->from('globus_uploads_downloads')
                                         ->where('loading', true);
                                   })->get();
    }
}