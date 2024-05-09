<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use App\Models\User;

class RefreshPublishedDatasetAction
{
    public function execute(Dataset $dataset, User $user)
    {
        $unpublishDatasetAction = new UnpublishDatasetAction();
        $unpublishDatasetAction($dataset, $user, true);
    }
}
