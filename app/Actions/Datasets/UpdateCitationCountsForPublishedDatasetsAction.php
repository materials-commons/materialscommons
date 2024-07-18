<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use function config;

class UpdateCitationCountsForPublishedDatasetsAction
{
    public function execute()
    {
        $getAndSavePublishedDatasetCitationsAction = new GetAndSavePublishedDatasetCitationsAction();
        $cursor = Dataset::with('papers')
                         ->whereDoesntHave('tags', function ($q) {
                             $q->where('tags.id', config('visus.import_tag_id'));
                         })
                         ->whereNotNull('published_at')
                         ->cursor();
        foreach ($cursor as $ds) {
            $getAndSavePublishedDatasetCitationsAction->execute($ds);
        }
    }
}