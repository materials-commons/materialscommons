<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use App\Models\User;

class RefreshPublishedDatasetAction
{
    public function execute(Dataset $dataset, User $user)
    {
        $unpublishDatasetAction = new UnpublishDatasetAction();
        $unpublishDatasetAction($dataset, $user);
        $this->publishDataset($dataset, $user);
    }


    private function publishDataset(Dataset $dataset, $user)
    {
        $publishAction = new PublishDatasetAction2();
        $publishAction->execute($dataset, $user);
    }
}
