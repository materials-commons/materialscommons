<?php

namespace App\Actions\Datasets;

use App\Models\Dataset;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class FinishPublishingDatasetAction
{
    // Run this only as a background task
    public function __invoke($datasetId)
    {
        $dataset = Dataset::with('entities')->findOrFail($datasetId);
        $this->publishActivities($dataset);
        $this->buildZipfile($dataset);
    }

    private function publishActivities(Dataset $dataset)
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

    private function buildZipfile(Dataset $dataset)
    {
        Artisan::call("mc:create-dataset-zipfile {$dataset->id} --create-dataset-files-table");
    }
}