<?php

namespace App\Console\Commands;

use App\Actions\Globus\ProcessFinishedGlobusUploadsAction;
use Illuminate\Console\Command;

class ProcessFinishedGlobusTasksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:process-finished-globus-tasks {--background}';

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
        $processUploadsInBackground = $this->option('background');
        $processFinishedGlobusUploadsAction = new ProcessFinishedGlobusUploadsAction();
        $processFinishedGlobusUploadsAction($processUploadsInBackground);
    }
}