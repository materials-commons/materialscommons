<?php

namespace App\Console\Commands;

use App\Models\File;
use App\Models\Project;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('mc:update-project-counts {project_id? : The ID of the project to update}')]
#[Description('Updates the file and directory counts for a project')]
class UpdateProjectCountsCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $projectId = $this->argument('project_id');

        if (blank($projectId)) {
            $this->updateAllProjects();
        } else {
            $project = Project::findOrFail($projectId);
            $this->updateProject($project);
        }

        return 0;
    }

    private function updateAllProjects()
    {
        Project::chunk(100, function ($projects) {
            foreach ($projects as $project) {
                $this->updateProject($project);
            }
        });
    }

    private function updateProject($project)
    {
        echo "Updating project {$project->name} ({$project->id})...\n";
        $filesCount = File::where('project_id', $project->id)
                          ->activeFiles()
                          ->where('mime_type', '<>', 'directory')
                          ->count();
        $directoryCount = File::where('project_id', $project->id)
                              ->activeFiles()
                              ->where('mime_type', 'directory')
                              ->count();
        $project->update([
            'file_count'      => $filesCount,
            'directory_count' => $directoryCount,
        ]);
    }
}
