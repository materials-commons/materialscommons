<?php

namespace App\Actions\Globus\Uploads;

use App\Actions\Globus\GlobusApi;
use App\Enums\GlobusStatus;
use App\Jobs\Globus\ImportGlobusUploadJob;
use App\Models\GlobusUploadDownload;
use Illuminate\Database\Eloquent\Collection;

class ProcessFinishedGlobusUploadsAction
{
    public function __invoke($processUploadsInBackground, $dryRun)
    {
        $getFinishedGlobusUploadsAction = new GetFinishedGlobusUploadsAction();
        $finishedUploads = $getFinishedGlobusUploadsAction();
        $uniqueByProjectUploads = $finishedUploads->unique(function (GlobusUploadDownload $upload) {
            return $upload->project_id;
        });

        if ($dryRun) {
            $this->showJobsToProcess($finishedUploads, $uniqueByProjectUploads);
            return;
        }

        $this->processJobs($uniqueByProjectUploads, $processUploadsInBackground);
    }

    private function processJobs($uniqueByProjectUploads, $processUploadsInBackground)
    {
        $maxItemsToProcess = config('globus.max_items');
        foreach ($uniqueByProjectUploads as $upload) {
            $upload->update(['status' => GlobusStatus::Loading]);
            if ($processUploadsInBackground) {
                $importGlobusUploadJob = new ImportGlobusUploadJob($upload, $maxItemsToProcess);
                dispatch($importGlobusUploadJob)->onQueue('globus');
            } else {
                $globusApi = GlobusApi::createGlobusApi();
                $importGlobusUploadInProjectAction = new ImportGlobusUploadIntoProjectAction($upload,
                    $maxItemsToProcess,
                    $globusApi);
                $importGlobusUploadInProjectAction();
            }
        }
    }

    private function showJobsToProcess(Collection $finishedUploads, Collection $uniqueByProjectUploads)
    {
        echo "The following uploads are finished: \n";
        foreach ($finishedUploads as $finishedUpload) {
            echo "{$finishedUpload->id} for project {$finishedUpload->project_id}\n";
        }

        echo "\nThe following would be started: \n";
        foreach ($uniqueByProjectUploads as $u) {
            echo "{$u->id} for project {$u->project_id}\n";
        }
    }
}