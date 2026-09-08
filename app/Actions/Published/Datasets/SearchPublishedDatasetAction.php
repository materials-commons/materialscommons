<?php

namespace App\Actions\Published\Datasets;

use App\Models\Activity;
use App\Models\Entity;
use App\Models\File;
use Illuminate\Support\Collection;

class SearchPublishedDatasetAction
{
    public function execute($search, $datasetId)
    {
        $results = new Collection();

        // Search files
        $fileResults = File::search($search)
            ->whereNull('deleted_at')
            ->where('current', true)
            ->where('dataset_id', $datasetId)
            ->take(10)
            ->get();
        $results = $results->concat($fileResults);

        // Search entities
        $entityResults = Entity::search($search)
            ->where('project_id', $datasetId)
            ->take(10)
            ->get();
        $results = $results->concat($entityResults);

        // Search activities
        $activityResults = Activity::search($search)
            ->where('project_id', $datasetId)
            ->take(10)
            ->get();
        $results = $results->concat($activityResults);

        return $results;
    }
}