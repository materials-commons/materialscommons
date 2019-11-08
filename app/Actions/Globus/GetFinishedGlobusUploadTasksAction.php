<?php

namespace App\Actions\Globus;

class GetFinishedGlobusUploadTasksAction
{
    /** @var \App\Actions\Globus\GlobusApi */
    private $globusApi;
    private $endpointId;
    private $numDays;

    public function __construct($numDays, $endpointId, GlobusApi $globusApi)
    {
        $this->endpointId = $endpointId;
        $this->globusApi = $globusApi;
        $this->numDays = $numDays;
    }

    public function __invoke()
    {
        $tasks = $this->globusApi->getEndpointTaskList($this->endpointId, 7);
        return collect($tasks['DATA'])->filter(function($task) {
            $transfers = $this->globusApi->getTaskSuccessfulTransfers($task['task_id']);
            if (sizeof($transfers['DATA']) === 0) {
                // There were no transfers
                return false;
            }

            $transferItem = $transfers['DATA'][0];
            if ($transferItem['destination_path'] === '') {
                // Download task
                return false;
            }

            $task['transfers'] = $transfers;

            // Transfer has files
            return true;
        });
    }
}