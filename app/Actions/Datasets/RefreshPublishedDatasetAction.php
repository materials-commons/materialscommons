<?php

namespace App\Actions\Datasets;

use App\Actions\Globus\GlobusApi;
use App\Models\Dataset;

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
        $unpublishDatasetAction = new UnpublishDatasetAction(GlobusApi::createGlobusApi());
        $unpublishDatasetAction($dataset);
        $this->publishDataset($dataset);
    }


    private function publishDataset(Dataset $dataset)
    {
        $publishAction = new PublishDatasetAction2(GlobusApi::createGlobusApi());
        $publishAction->execute($dataset);
    }
}