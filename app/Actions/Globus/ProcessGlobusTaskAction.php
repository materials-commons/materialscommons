<?php

namespace App\Actions\Globus;

use App\Jobs\Globus\ImportGlobusUploadJob;
use App\Models\GlobusUpload;

class ProcessGlobusTaskAction
{
    /** @var \App\Actions\Globus\GlobusApi */
    private $globusApi;
    private $endpointId;

    public function __construct(GlobusApi $globusApi, $endpointId)
    {
        $this->globusApi = $globusApi;
        $this->endpointId = $endpointId;
    }

    public function __invoke($task, $processTaskInBackground)
    {
        $transfers = $task['transfers'];
        $transferItem = $transfers['DATA'][0];
        $pathPieces = explode("/", $transferItem['destination_path']);
        $id = $pathPieces[2];

        $upload = GlobusUpload::find($id);
        if ($upload === null) {
            // task already processed
            return;
        }

        if ($upload->loading) {
            // Task being processed
            return;
        }

        $this->globusApi->deleteEndpointAclRule($this->endpointId, $upload->globus_acl_id);

        if ($processTaskInBackground) {
            $importGlobusUploadJob = new ImportGlobusUploadJob($upload);
            dispatch($importGlobusUploadJob);
        } else {
            $loadGlobusUploadInProjectAction = new LoadGlobusUploadIntoProjectAction($upload, 1000);
            $loadGlobusUploadInProjectAction();
        }
    }
}