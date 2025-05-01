<?php

namespace App\Http\Controllers\Web\Datasets\Traits;

use App\Models\Dataset;

trait DatasetEntities
{
    private function getEntitiesForDataset(Dataset $dataset, $category = 'experimental')
    {
        // unpublished dataset so get entities from template
        if (is_null($dataset->published_at)) {
            return $dataset->entitiesFromTemplate($category);
        }

        // published dataset so get the entities that were associated with
        // the dataset from iterating through the template
        $dataset->load([
            'entities' => function ($query) use ($category) {
                $query->with(['files.directory'])->where('category', $category);
            }
        ]);

        if ($dataset->entities->count() == 0) {
            // The published dataset entities may not have been built yet. If they haven't then
            // return the entities from the template so that the dataset status doesn't change
            // in the status display.
            return $dataset->entitiesFromTemplate($category);
        }
        return $dataset->entities;
    }
}