<?php

namespace App\Actions\Globus\Uploads;

use App\Actions\Globus\GlobusApi;
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
        $maxItemsToProcess = config('globus.max_items');
        foreach ($uniqueByProjectUploads as $upload) {
            $upload->update(['status' => GlobusStatus::Loading]);
            if ($processUploadsInBackground) {
                $importGlobusUploadJob = new ImportGlobusUploadJob($upload, $maxItemsToProcess);
                dispatch($importGlobusUploadJob)->onQueue('globus');
            } else {
                $globusApi = GlobusApi::createGlobusApi();
                $loadGlobusUploadInProjectAction = new LoadGlobusUploadIntoProjectAction($upload, $maxItemsToProcess,
                    $globusApi);
                $loadGlobusUploadInProjectAction();
            }
        }
    }

}