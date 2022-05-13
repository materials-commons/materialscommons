<?php

namespace App\Console\Commands;

use App\Actions\Projects\CopyProjectAction;
use App\Models\Project;
use Illuminate\Console\Command;

class CopyProjectFilesIntoNewProjectCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:copy-project-files-into-new-project {fromProjectId} {toProjectId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy all files/directories from fromProject to toProject';

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
     * @return int
     */
    public function handle()
    {
        $fromProject = Project::findOrfail($this->argument('fromProjectId'));
        $toProject = Project::findOrFail($this->argument('toProjectId'));
        $copyProjectAction = new CopyProjectAction();
        $copyProjectAction->copyProject($fromProject, $toProject);
        return 0;
    }
}
