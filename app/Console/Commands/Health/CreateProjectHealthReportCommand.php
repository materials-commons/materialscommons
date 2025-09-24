<?php

namespace App\Console\Commands\Health;

use App\Actions\Projects\CreateProjectHealthReportAction;
use App\Models\Project;
use Illuminate\Console\Command;

class CreateProjectHealthReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-health:create-project-health-report {project : Project id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a health report for a project';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $projectId = $this->argument('project');
        $project = Project::findOrFail($projectId);
        $this->info("Creating health report for project {$project->name}");
        $createHealthReport = new CreateProjectHealthReportAction($project);
        $createHealthReport->execute();
    }
}
