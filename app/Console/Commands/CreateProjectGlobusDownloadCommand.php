<?php

namespace App\Console\Commands;

use App\Actions\Globus\Downloads\CreateGlobusDownloadForProjectAction;
use App\Actions\Globus\Downloads\CreateGlobusProjectDownloadDirsAction;
use App\Actions\Globus\GlobusApi;
use App\Models\Project;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CreateProjectGlobusDownloadCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:create-project-globus-download {projectId : Project Id to create for} {--user= : User id to use}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create download for given project';

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
        ini_set("memory_limit", "4096M");
        $projectId = $this->argument("projectId");
        $project = Project::with('owner')->findOrFail($projectId);
        $userId = $this->option('user');
        if (is_null($userId)) {
            $userId = $project->owner_id;
        }

        $user = User::findOrFail($userId);
        $createGlobusDownloadAction = new CreateGlobusDownloadForProjectAction();
        $now = now();
        $data = ['name' => "{$project->name}_download_{$now}"];

        $download = $createGlobusDownloadAction($data, $project->id, $user);
        $downloadPath = Storage::disk('mcfs')->path("__globus_downloads/{$download->uuid}");
        echo "Creating download in {$downloadPath}\n";
        $now = now();
        echo "Starting build at ${now}\n";
        $createDirsAction = new CreateGlobusProjectDownloadDirsAction(GlobusApi::createGlobusApi());
        $createDirsAction($download, $user);
        $now = now();
        echo "Finished build at ${now}\n";
    }
}
