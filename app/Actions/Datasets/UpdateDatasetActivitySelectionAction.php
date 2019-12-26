<?php

namespace App\Actions\Datasets;

class UpdateDatasetActivitySelectionAction
{
    public function __invoke($activityId, $dataset)
    {
        $dataset->activities()->toggle($activityId);
        return $dataset;
    }
}