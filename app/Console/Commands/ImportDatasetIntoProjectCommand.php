<?php

namespace App\Console\Commands;

use App\Actions\Datasets\ImportDatasetIntoProjectAction;
use App\Models\Dataset;
use App\Models\Project;
use Illuminate\Console\Command;

class ImportDatasetIntoProjectCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:import-dataset-into-project {datasetId : Dataset to import} 
                                                           {--project-id= : Project to import into}';

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
        $dataset = Dataset::findOrFail($this->argument("datasetId"));
        $project = Project::findOrFail($this->option("project-id"));
        $importDatasetIntoProjectAction = new ImportDatasetIntoProjectAction();
        $importDatasetIntoProjectAction->execute($dataset, $project, $dataset->importDirectory());
    }
}
