<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;

class RefreshPublishedDatasetAction
{
    public function execute(Dataset $dataset)
    {
        $unpublishDatasetAction = new UnpublishDatasetAction();
        $unpublishDatasetAction($dataset, auth()->user());
        $this->publishDataset($dataset, auth()->user());
    }


    private function publishDataset(Dataset $dataset)
    {
        $publishAction = new PublishDatasetAction2();
        $publishAction->execute($dataset, auth()->user());
    }
}
