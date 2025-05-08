<?php

namespace App\Http\Controllers\Web\Datasets\Traits;

use App\Models\Dataset;

trait DatasetEntities
{
    public function getEntitiesForDataset(Dataset $dataset, $category = 'experimental')
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

    // Get the mapping of dataset files to entities. The returned collection looks like:
    // file_id => Collection([
    //     [
    //         "file_id"     => file_id,
    //         "entity_name" => entity_name,
    //         "entity_id"   => entity_id
    //     ]
    // ])
    // Example:
    //  file_id => Collection([
    //      [
    //          "file_id"     => 1,
    //          "entity_name" => "MG1Y",
    //          "entity_id"   => 1
    //      ],
    //      [
    //          "file_id"     => 1,
    //          "entity_name" => "MG2Y_At_350C",
    //          "entity_id"   => 2
    //      ]
    // ])
    public function getDatasetFileToEntityMapping(Dataset $dataset, $category = 'experimental')
    {
        $entities = $this->getEntitiesForDataset($dataset, $category);
        return $entities->flatMap(function ($entity) {
            return $entity->files->map(function ($file) use ($entity) {
                return [
                    "file_id"     => $file->id,
                    "entity_name" => $entity->name,
                    "entity_id"   => $entity->id
                ];
            });
        })->groupBy("file_id");
    }
}