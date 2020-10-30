<?php

namespace App\Actions\Globus\Uploads;

use App\Enums\GlobusStatus;
use App\Enums\GlobusType;
use App\Models\GlobusUploadDownload;

class GetFinishedGlobusUploadsAction
{
    // Returns the list of globus uploads that are not in process and that **do not** have a project_id matching
    // an upload that is being processed.
    public function __invoke()
    {
        return GlobusUploadDownload::where('status', GlobusStatus::Done)
                                   ->where('type', GlobusType::ProjectUpload)
                                   ->where(function ($query) {
                                       $query->whereNull('errors')->orWhere('errors', '<', 10);
                                   })
                                   ->whereNotIn('project_id', function ($q) {
                                       $q->select('project_id')->from('globus_uploads_downloads')
                                         ->where('status', GlobusStatus::Loading);
                                   })->get();
    }
}