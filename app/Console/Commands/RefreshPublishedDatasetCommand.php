<?php

namespace App\Console\Commands;

use App\Actions\Datasets\RefreshPublishedDatasetAction;
use App\Actions\Globus\GlobusApi;
use App\Models\Dataset;
use Illuminate\Console\Command;

class RefreshPublishedDatasetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:refresh-published-dataset {dataset : id of dataset to refresh}';

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
     * @return int
     */
    public function handle()
    {
        $dataset = Dataset::findOrFail($this->argument('dataset'));
        $refreshPublishedDatasetAction = new RefreshPublishedDatasetAction(GlobusApi::createGlobusApi());
        $refreshPublishedDatasetAction->execute($dataset);
    }
}
