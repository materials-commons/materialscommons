<?php

namespace App\Actions\Datasets;

use App\Actions\Globus\GlobusApi;
use App\Models\Dataset;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RefreshPublishedDatasetAction
{
    /** @var \App\Actions\Globus\GlobusApi */
    private $globusApi;

    public function __construct(GlobusApi $globusApi)
    {
        $this->globusApi = $globusApi;
    }

    public function execute(Dataset $dataset)
    {
        $this->removeDatasetFiles($dataset);
        $this->buildDatasetFiles($dataset);
    }

    private function removeDatasetFiles(Dataset $dataset)
    {
        try {
            $this->globusApi->deleteEndpointAclRule($dataset->globus_endpoint_id, $dataset->globus_acl_id);
        } catch (\Exception $e) {
            Log::error("Unable to delete acl");
        }

        Storage::disk('mcfs')->deleteDirectory($dataset->publishedGlobusPathPartial());
        Storage::disk('mcfs')->deleteDirectory($dataset->zipfileDirPartial());
    }

    private function buildDatasetFiles(Dataset $dataset)
    {
        $syncActivitiesToPublishedDatasetAction = new SyncActivitiesToPublishedDatasetAction();
        $syncActivitiesToPublishedDatasetAction($dataset->id);

        $createDatasetZipfileAction = new CreateDatasetZipfileAction();
        $createDatasetZipfileAction($dataset->id, true);

        $createDatasetInGlobusAction = new CreateDatasetInGlobusAction($this->globusApi);
        $createDatasetInGlobusAction($dataset->id, false);
    }
}