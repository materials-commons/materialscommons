<?php

namespace App\Actions\Globus\Uploads;

use App\Enums\GlobusStatus;
use App\Jobs\Globus\ImportGlobusUploadJob;
use App\Models\GlobusUploadDownload;

class ProcessFinishedGlobusUploadsAction
{
    public function __invoke($processUploadsInBackground)
    {
        $getFinishedGlobusUploadsAction = new GetFinishedGlobusUploadsAction();
        $finishedUploads = $getFinishedGlobusUploadsAction();
        $uniqueByProjectUploads = $finishedUploads->unique(function (GlobusUploadDownload $upload) {
            return $upload->project_id;
        });
        foreach ($uniqueByProjectUploads as $upload) {
            $upload->update(['status' => GlobusStatus::Loading]);
            if ($processUploadsInBackground) {
                $importGlobusUploadJob = new ImportGlobusUploadJob($upload, 1000);
                dispatch($importGlobusUploadJob)->onQueue('globus');
            } else {
                $loadGlobusUploadInProjectAction = new LoadGlobusUploadIntoProjectAction($upload, 1000);
                $loadGlobusUploadInProjectAction();
            }
        }
    }

}