<?php

namespace App\Actions\Datasets;

class UpdateDatasetEntitySelectionAction
{
    public function __invoke($entityId, $dataset)
    {
        $dataset->entities()->toggle($entityId);
        return $dataset;
    }
}