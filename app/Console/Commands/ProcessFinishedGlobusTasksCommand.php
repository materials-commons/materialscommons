<?php

namespace App\Console\Commands;

use App\Actions\Globus\GetFinishedGlobusUploadTasksAction;
use App\Actions\Globus\GlobusApi;
use App\Actions\Globus\ProcessGlobusTaskAction;
use Illuminate\Console\Command;

class ProcessFinishedGlobusTasksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:process-finished-globus-tasks {--days-to-check=7} {--process-tasks} {--background}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process globus tasks that have finished downloading';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $endpointId = env('MC_GLOBUS_ENDPOINT_ID');
        $globusApi = new GlobusApi(env('MC_GLOBUS_CC_USER'), env('MC_GLOBUS_CC_TOKEN'));
        $globusApi->authenticate();

        $numDays = $this->option('days-to-check');
        $processTasks = $this->option('process-tasks');
        $processTasksInBackground = $this->option('background');

        $getFinishedGlobusUploadTasksAction = new GetFinishedGlobusUploadTasksAction($numDays, $endpointId, $globusApi);
        $tasks = $getFinishedGlobusUploadTasksAction();

        if ($processTasks) {
            $processGlobusTaskAction = new ProcessGlobusTaskAction($globusApi, $endpointId);
            $tasks->each(function($task) use ($processGlobusTaskAction, $processTasksInBackground) {
                $processGlobusTaskAction($task, $processTasksInBackground);
            });
        }
    }
}