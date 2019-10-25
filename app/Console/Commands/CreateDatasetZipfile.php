<?php

namespace App\Console\Commands;

use App\Actions\Datasets\CreateDatasetZipfileAction;
use Illuminate\Console\Command;

class CreateDatasetZipfile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:create-dataset-zipfile {dataset : id of dataset to create} {--create-dataset-files-table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the zipfile for a dataset';

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
        $datasetId = $this->argument('dataset');
        $createDatasetFilesTable = $this->option('create-dataset-files-table');
        $createDatasetZipfileAction = new CreateDatasetZipfileAction();
        $createDatasetZipfileAction($datasetId, $createDatasetFilesTable);
    }
}
