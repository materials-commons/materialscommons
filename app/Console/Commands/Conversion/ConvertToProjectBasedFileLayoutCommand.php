<?php

namespace App\Console\Commands\Conversion;

use App\Models\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use function ini_set;

class ConvertToProjectBasedFileLayoutCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-transform:convert-to-project-based-file-layout  {projectId : Project Id for project to convert (all for all projects)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set("memory_limit", "4096M");
        $projectId = $this->argument("projectId");
        if ($projectId == "all") {
            $this->convertAllProjects();
        } else {
            $this->convertProject(Project::findOrFail($projectId));
        }
    }

    private function convertAllProjects(): void
    {
        foreach (Project::cursor() as $project) {
            $this->convertProject($project);
        }
    }

    private function convertProject(Project $project)
    {
        Storage::disk('mcfs')->path("");
        $mcfsBase = config('filesystems.disks.mcfs.root');
    }
}
