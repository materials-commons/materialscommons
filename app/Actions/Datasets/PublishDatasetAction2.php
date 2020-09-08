<?php

namespace App\Actions\Datasets;

use App\Actions\Globus\GlobusApi;
use App\Models\Dataset;

class PublishDatasetAction2
{
    /**
     * @var \App\Actions\Globus\GlobusApi
     */
    private $globusApi;

    public function __construct(GlobusApi $globusApi)
    {
        $this->globusApi = $globusApi;
    }

    public function execute(Dataset $dataset)
    {
        $this->syncActivities($dataset);
        $this->replicateFiles($dataset);
        $this->replicateEntitiesAndRelatedItems($dataset);
        $this->buildGlobusDownload($dataset);
        $this->buildZipfile($dataset);
    }

    private function syncActivities(Dataset $dataset)
    {
        $syncAction = new SyncActivitiesToPublishedDatasetAction();
        $syncAction($dataset->id);
    }

    private function replicateFiles(Dataset $dataset)
    {
        $createDatasetFilesTableAction = new CreateDatasetFilesTableAction();
        $createDatasetFilesTableAction->execute($dataset);
    }

    private function replicateEntitiesAndRelatedItems(Dataset $dataset)
    {
        $replicator = new ReplicateDatasetEntitiesAndRelationshipsAction();
        $replicator->execute($dataset);
    }

    private function buildGlobusDownload(Dataset $dataset)
    {
        $createDatasetInGlobusAction = new CreateDatasetInGlobusAction($this->globusApi);
        $createDatasetInGlobusAction($dataset, false);
    }

    private function buildZipfile(Dataset $dataset)
    {
        $createDatasetZipfileAction = new CreateDatasetZipfileAction();
        $createDatasetZipfileAction($dataset);
    }
}