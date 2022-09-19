<?php

namespace App\Console\Commands;

use App\Actions\Datasets\CreateDatasetFilesTableAction;
use App\Actions\Datasets\CreateDatasetInGlobusAction;
use App\Models\Dataset;
use Illuminate\Console\Command;

class CreateDatasetInGlobusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:create-dataset-in-globus {datasetId : Dataset to place in globus}
                                                        {--private : Make dataset private}
                                                        {--create-dataset-files-table}';

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
        $dataset = Dataset::findOrFail($this->argument('datasetId'));

        if ($this->option('create-dataset-files-table')) {
            (new CreateDatasetFilesTableAction())->execute($dataset);
        }

        $createDatasetInGlobusAction = new CreateDatasetInGlobusAction();
        $createDatasetInGlobusAction($dataset);
    }
}
