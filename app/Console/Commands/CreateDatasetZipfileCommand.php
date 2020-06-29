<?php

namespace App\Console\Commands;

use App\Actions\Datasets\CreateDatasetFilesTableAction;
use App\Actions\Datasets\CreateDatasetZipfileAction;
use App\Models\Dataset;
use Illuminate\Console\Command;

class CreateDatasetZipfileCommand extends Command
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
        $dataset = Dataset::findOrFail($this->argument('dataset'));
        if ($this->option('create-dataset-files-table')) {
            (new CreateDatasetFilesTableAction())->execute($dataset);
        }
        $createDatasetZipfileAction = new CreateDatasetZipfileAction();
        $createDatasetZipfileAction($dataset);
    }
}
