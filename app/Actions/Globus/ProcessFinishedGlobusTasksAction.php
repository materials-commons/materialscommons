<?php

namespace App\Actions\Globus;

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
        $this->numDays = $numDays;
    }

    public function __invoke()
    {
        $getFinishedGlobusUploadTasksAction = new GetFinishedGlobusUploadTasksAction($this->numDays, $this->endpointId,
            $this->globusApi);
        $tasks = $getFinishedGlobusUploadTasksAction();
        $processGlobusTaskAction = new ProcessGlobusTaskAction($this->globusApi, $this->endpointId);
        $tasks->each(function($task) use ($processGlobusTaskAction) {
            $processGlobusTaskAction($task, true);
        });
    }
}

