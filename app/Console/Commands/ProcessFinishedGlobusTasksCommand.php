<?php

namespace App\Console\Commands;

use App\Actions\Globus\Uploads\ProcessFinishedGlobusUploadsAction;
use Illuminate\Console\Command;

class ProcessFinishedGlobusTasksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:process-finished-globus-tasks {--background} {--dry-run} {--log}';

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
        $processFinishedGlobusUploadsAction = new ProcessFinishedGlobusUploadsAction();
        $processFinishedGlobusUploadsAction($this->option('background'),
            $this->option('dry-run'),
            $this->option('log'));
    }
}