<?php

namespace App\Actions\Datasets;

use Illuminate\Support\Facades\DB;

class SyncActivitiesToPublishedDatasetAction
{
    // Run this only as a background task
    public function execute($dataset)
    {
        $dataset->activities()
                ->sync(
                    DB::table('dataset2entity')
                      ->where('dataset2entity.dataset_id', 1)
                      ->join('activity2entity', 'dataset2entity.entity_id', '=',
                          'activity2entity.entity_id')
                      ->select('activity2entity.activity_id')
                      ->distinct()
                      ->get()
                      ->map(function($value) {
                          return $value->activity_id;
                      })
                      ->toArray()
                );
    }
}