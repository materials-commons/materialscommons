<?php

namespace App\Jobs\Projects;

use App\Actions\Projects\CreateProjectHealthReportAction;
use App\Models\Project;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CreateProjectHealthReportJob implements ShouldQueue
{
    use Queueable;

    public Project $project;

    /**
     * Create a new job instance.
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $projectHealthReport = new CreateProjectHealthReportAction($this->project);
        $projectHealthReport->execute();
    }
}
