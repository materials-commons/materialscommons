<?php

namespace App\Jobs\Datasets;

use App\Actions\Datasets\CreateDatasetFilesTableAction;
use App\Actions\Datasets\CreateDatasetInGlobusAction;
use App\Actions\Datasets\CreateDatasetZipfileAction;
use App\Actions\Datasets\SyncActivitiesToPublishedDatasetAction;
use App\Actions\Globus\GlobusApi;
use App\Models\Dataset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PublishDatasetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $datasetId;

    public function __construct($datasetId)
    {
        $this->datasetId = $datasetId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dataset = Dataset::findOrFail($this->datasetId);
        $syncActivitiesToPublishedDatasetAction = new SyncActivitiesToPublishedDatasetAction();
        $syncActivitiesToPublishedDatasetAction($dataset->id);

        $createDatasetFilesTableAction = new CreateDatasetFilesTableAction();
        $createDatasetFilesTableAction->execute($dataset);

        $createDatasetZipfileAction = new CreateDatasetZipfileAction();
        $createDatasetZipfileAction($dataset);

        $createDatasetInGlobusAction = new CreateDatasetInGlobusAction(GlobusApi::createGlobusApi());
        $createDatasetInGlobusAction($dataset, false);
    }
}
