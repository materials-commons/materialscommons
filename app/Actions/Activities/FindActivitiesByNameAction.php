<?php

namespace App\Actions\Activities;

use App\Models\Activity;
use Illuminate\Support\Facades\DB;
use Spatie\Searchable\ModelSearchAspect;
use Spatie\Searchable\Search;

class FindActivitiesByNameAction
{
    private $projectId = null;
    private $experimentId = null;
    private $datasetId = null;

    public function execute($search)
    {
        $activityIds = $this->getActivityIdsToSearchThrough();

        return (new Search())
            ->registerModel(Activity::class, function (ModelSearchAspect $modelSearchAspect) use ($activityIds) {
                $modelSearchAspect->addExactSearchableAttribute('name')
                                  ->whereIn('id', $activityIds);
            })
            ->search($search);
    }

    public function limitToProject($projectId)
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function limitToExperiment($experimentId)
    {
        $this->experimentId = $experimentId;

        return $this;
    }

    public function limitToDataset($datasetId)
    {
        $this->datasetId = $datasetId;

        return $this;
    }

    private function getActivityIdsToSearchThrough()
    {
        if (isset($this->projectId)) {
            return $this->getActivitityIdsForProject();
        }

        if (isset($this->experimentId)) {
            return $this->getActivityIdsForExperiment();
        }

        if (isset($this->datasetId)) {
            return $this->getActivityIdsForDataset();
        }

        return [];
    }

    private function getActivitityIdsForProject()
    {
        return DB::table('activities')->where('project_id', $this->projectId)->select('id')->get()->map(function ($obj) {
            return $obj->id;
        })->toArray();
    }

    private function getActivityIdsForExperiment()
    {
        return DB::Table('experiment2activity')->where('experiment_id', $this->experimentId)->select('activity_id')->get()
                 ->map(function ($obj) {
                     return $obj->activity_id;
                 })
                 ->toArray();
    }

    private function getActivityIdsForDataset()
    {
        return DB::table('dataset2activity')->where('dataset_id', $this->datasetId)->select('activity_id')->get()
                 ->map(function ($obj) {
                     return $obj->activity_id;
                 })
                 ->toArray();
    }
}