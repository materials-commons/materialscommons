<?php

namespace App\Console\Commands;

use App\Enums\ExperimentStatus;
use App\Imports\Etl\EntityActivityImporter;
use App\Imports\Etl\EtlState;
use App\Models\Experiment;
use App\Models\File;
use App\Models\Project;
use App\Traits\PathForFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CreateExperimentFromSpreadsheetCommand extends Command
{
    use PathForFile;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:create-experiment-from-spreadsheet {projectId : ProjectId}
                                                                  {--experiment-name= : experiment name}
                                                                  {--file-id= : spreadsheet file id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $experimentName = $this->option("experiment-name");
        $fileId = $this->option("file-id");
        $this->createExperiment($projectId, $experimentName, $fileId);
    }

    private function createExperiment($projectId, $name, $fileId)
    {
        $project = Project::findOrFail($projectId);
        $experiment = new Experiment(['name' => $name, 'project_id' => $projectId]);
        $experiment->owner_id = $project->owner_id;
        $experiment->status = ExperimentStatus::InProgress;
        $experiment->save();
        $experiment->refresh();

        $file = File::findOrFail($fileId);
        $etlState = new EtlState($project->owner_id, $file->id);
        $experiment->etlruns()->save($etlState->etlRun);

        $uuidPath = $this->getFilePathForFile($file);
        $filePath = Storage::disk('mcfs')->path("{$uuidPath}");
        $importer = new EntityActivityImporter($projectId, $experiment->id, $project->owner_id, $etlState);
        $importer->execute($filePath);
    }
}
