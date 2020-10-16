<?php

namespace App\Http\Controllers\Web\Datasets\Traits;

trait HasExtendedInfo
{
    private function getEntitiesForDataset($dataset)
    {
        // unpublished dataset so get entities from template
        if (is_null($dataset->published_at)) {
            return $dataset->entitiesFromTemplate();
        }

        // published dataset so get the entities that were associated with
        // the dataset from iterating through the template
        $dataset->load('entities');

        if ($dataset->entities->count() == 0) {
            // The published dataset entities may not have been built yet. If they haven't then
            // return the entities from the template so that the dataset status doesn't change
            // in the status display.
            return $dataset->entitiesFromTemplate();
        }
        return $dataset->entities;
    }
}