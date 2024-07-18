<?php

namespace App\Console\Commands\Datasets;

use App\Actions\Datasets\UpdateCitationCountsForPublishedDatasetsAction;
use Illuminate\Console\Command;

class UpdateCitationCountsForPublishedDatasetsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-datasets:update-citation-counts-for-published-datasets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update citation counts for published datasets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $updatePublishedDatasetsCitations = new UpdateCitationCountsForPublishedDatasetsAction();
        $updatePublishedDatasetsCitations->execute();
    }
}
