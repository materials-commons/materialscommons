<?php

namespace App\Console\Commands;

use App\Actions\Datasets\CreateDatasetInGlobusAction;
use App\Actions\Globus\GlobusApi;
use Illuminate\Console\Command;

class CreateDatasetInGlobusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:create-dataset-in-globus {datasetId : Dataset to place in globus}
                                                        {--private : Make dataset private}';

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
        $datasetId = $this->argument('datasetId');
        $isPrivate = $this->option('private');
        $globusApi = GlobusApi::createGlobusApi();
        $createDatasetInGlobusAction = new CreateDatasetInGlobusAction($globusApi);
        $createDatasetInGlobusAction($datasetId, $isPrivate);
    }
}
