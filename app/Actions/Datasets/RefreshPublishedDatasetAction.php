<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;

class RefreshPublishedDatasetAction
{
    public function execute(Dataset $dataset)
    {
        $unpublishDatasetAction = new UnpublishDatasetAction();
        $unpublishDatasetAction($dataset);
        $this->publishDataset($dataset);
    }


    private function publishDataset(Dataset $dataset)
    {
        $publishAction = new PublishDatasetAction2();
        $publishAction->execute($dataset);
    }
}