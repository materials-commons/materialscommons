<?php

namespace App\Traits\Experiments;

use App\Models\Dataset;
use App\Models\Experiment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait SharedDatasets
{
    private function getDatasetsListSharingEntitiesWithExperiment(Experiment $experiment)
    {
        $experiment->load('entities');
        return DB::table('dataset2entity')
                 ->select('dataset_id')
                 ->whereIn('entity_id',
                     $experiment->entities->pluck('id')->toArray())
                 ->distinct()
                 ->get()
                 ->pluck('dataset_id');
    }

    private function getAffectedPublishedDatasets(Collection $datasetIds)
    {
        if ($datasetIds->isEmpty()) {
            return collect([]);
        }

        return Dataset::whereIn('id', $datasetIds->toArray())
                      ->whereNotNull('published_at')
                      ->get();
    }

    private function getAffectedUnpublishedDatasets(Collection $datasetIds)
    {
        if ($datasetIds->isEmpty()) {
            return collect([]);
        }

        return Dataset::whereIn('id', $datasetIds->toArray())
                      ->whereNull('published_at')
                      ->get();
    }

    private function hasAffectedPublishedDatasets(Experiment $experiment)
    {
        $datasetsIds = $this->getDatasetsListSharingEntitiesWithExperiment($experiment);
        if ($datasetsIds->isEmpty()) {
            return false;
        }

        return $this->getAffectedUnpublishedDatasets($datasetsIds)->isNotEmpty();
    }
}