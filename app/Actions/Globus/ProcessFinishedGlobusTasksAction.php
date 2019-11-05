<?php

use App\Actions\Globus\GlobusApi;
use App\Models\GlobusUpload;

class ProcessFinishedGlobusTasksAction
{
    /** @var \App\Actions\Globus\GlobusApi */
    private $globusApi;
    private $endpointId;
    private $numDays;

    public function __construct($numDays, $endpointId, GlobusApi $globusApi)
    {
        $this->endpointId = $endpointId;
        $this->globusApi = $globusApi;
        $this->$numDays = $numDays;
    }

    public function __invoke()
    {
        $tasks = $this->globusApi->getEndpointTaskList($this->endpointId, 7);
        foreach ($tasks['DATA'] as $task) {
            $transfers = $this->globusApi->getTaskSuccessfulTransfers($task['task_id']);
            if (sizeof($transfers['DATA']) === 0) {
                // There were no transfers
                continue;
            }

            $this->processTransfers($transfers);
        }
    }

    private function processTransfers($transfers)
    {
        $transferItem = $transfers['DATA'][0];
        if ($transferItem['destination_path'] === '') {
            // This was a Download task - skip
            return;
        }
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

        // TODO: Kick off a background task to process this entry
    }
}

