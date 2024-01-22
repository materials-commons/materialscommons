<?php

namespace App\Actions\Published\Datasets;

use App\Models\Activity;
use App\Models\Attribute;
use App\Models\Community;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\Experiment;
use App\Models\File;
use App\Models\Workflow;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Searchable\ModelSearchAspect;
use Spatie\Searchable\Search;

class SearchPublishedDatasetAction
{
    public function execute($search, $datasetId)
    {
        return (new Search())
            ->registerModel(File::class, function (ModelSearchAspect $modelSearchAspect) use ($datasetId) {
                $modelSearchAspect->addSearchableAttribute('name')
                                  ->addSearchableAttribute('description')
                                  ->addSearchableAttribute('path')
                                  ->addSearchableAttribute('mime_type')
                                  ->addSearchableAttribute('media_type_description')
                                  ->with(['directory'])
                                  ->whereNull('deleted_at')
                                  ->where('current', true)
                                  ->where('dataset_id', $datasetId);
            })
            ->registerModel(Entity::class, function (ModelSearchAspect $modelSearchAspect) use ($datasetId) {
                $modelSearchAspect->addSearchableAttribute('name')
                                  ->addSearchableAttribute('description')
                                  ->where('project_id', $datasetId);
            })
            ->registerModel(Activity::class, function (ModelSearchAspect $modelSearchAspect) use ($datasetId) {
                $modelSearchAspect->addSearchableAttribute('name')
                                  ->addSearchableAttribute('description')
                                  ->where('project_id', $datasetId);
            })
            // This is way too expensive at the moment. Come back too later to allow searches on attributes.
//            ->registerModel(Attribute::class, function (ModelSearchAspect $modelSearchAspect) use ($datasetId) {
//                $modelSearchAspect->addSearchableAttribute('name')
//                                  ->where(function ($query) {
//                                      $query
//                                          ->where("attributable_type", "App\\Models\\Activity")
//                                          ->whereIn("attributable_id", function ($query) {
//                                              $query
//                                                  ->select("id")
//                                                  ->from("activities")
//                                                  ->where("dataset_id", 218);
//                                          });
//                                  })
//                                  ->orWhere(function ($query) {
//                                      $query
//                                          ->where("attributable_type", "App\\Models\\EntityState")
//                                          ->whereIn("attributable_id", function ($query) {
//                                              $query
//                                                  ->select("id")
//                                                  ->from("entity_states")
//                                                  ->whereIn("entity_id", function ($query) {
//                                                      $query
//                                                          ->select("id")
//                                                          ->from("entities")
//                                                          ->where("dataset_id", 218);
//                                                  });
//                                          });
//                                  })
//                                  ->limit(10);
//            })
            ->limitAspectResults(10)
            ->search($search);
    }
}