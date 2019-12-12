<?php

namespace App\Actions\Globus;

use App\Jobs\Globus\ImportGlobusUploadJob;
use App\Models\GlobusUpload;

class ProcessFinishedGlobusUploadsAction
{
    public function __invoke($processUploadsInBackground)
    {
        $getFinishedGlobusUploadsAction = new GetFinishedGlobusUploadsAction();
        $finishedUploads = $getFinishedGlobusUploadsAction();
        $uniqueByProjectUploads = $finishedUploads->unique(function (GlobusUpload $upload) {
            return $upload->project_id;
        });
        foreach ($uniqueByProjectUploads as $upload) {
            $upload->update(['loading' => true]);
            if ($processUploadsInBackground) {
                $importGlobusUploadJob = new ImportGlobusUploadJob($upload, 1000);
                dispatch($importGlobusUploadJob);
            } else {
                $loadGlobusUploadInProjectAction = new LoadGlobusUploadIntoProjectAction($upload, 1000);
                $loadGlobusUploadInProjectAction();
            }
        }
    }

}