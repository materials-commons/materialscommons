<?php

namespace App\Traits;

use App\Models\Activity;
use App\Models\EntityState;
use Illuminate\Support\Facades\DB;

trait DataDictionaryQueries
{
    public function getUniqueActivityAttributesForExperiment($experimentId)
    {
        return DB::table('attributes')
                 ->select('name', 'unit', 'val')
                 ->whereIn(
                     'attributable_id',
                     DB::table('experiment2activity')
                       ->select('activity_id')
                       ->where('experiment_id', $experimentId)
                 )
                 ->where('attributable_type', Activity::class)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.attribute_id')
                 ->orderBy('name')
                 ->distinct()
                 ->get()
                 ->groupBy('name');
    }

    public function getActivityAttributeForExperiment($experimentId, $attrName)
    {
        return DB::table('attributes')
                 ->select('attributes.name', 'attribute_values.unit', 'attribute_values.val', 'attributes.id',
                     'activities.name as object_name', 'activities.id as object_id',
                     DB::raw("'activity' as object_type"))
                 ->whereIn(
                     'attributable_id',
                     DB::table('experiment2activity')
                       ->select('activity_id')
                       ->where('experiment_id', $experimentId)
                 )
                 ->where('attributable_type', Activity::class)
                 ->where('attributes.name', $attrName)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.attribute_id')
                 ->join('activities', 'attributes.attributable_id', '=', 'activities.id')
                 ->orderBy('name')
                 ->distinct()
                 ->get()
                 ->groupBy('name');
    }

    public function getUniqueEntityAttributesForExperiment($experimentId)
    {
        return DB::table('attributes')
                 ->select('name', 'unit', 'val')
                 ->whereIn(
                     'attributable_id',
                     DB::table('experiment2entity')
                       ->select('entity_states.id')
                       ->where('experiment_id', $experimentId)
                       ->join('entity_states', 'experiment2entity.entity_id', '=', 'entity_states.entity_id')

                 )
                 ->where('attributable_type', EntityState::class)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.attribute_id')
                 ->distinct()
                 ->get()
                 ->groupBy('name');
    }

    public function getEntityAttributeForExperiment($experimentId, $attrName)
    {
        return DB::table('attributes')
                 ->select('attributes.name', 'attribute_values.unit', 'attribute_values.val', 'attributes.id',
                     'entities.name as object_name', 'entities.id as object_id', DB::raw("'entity' as object_type"))
                 ->whereIn(
                     'attributable_id',
                     DB::table('experiment2entity')
                       ->select('entity_states.id')
                       ->where('experiment_id', $experimentId)
                       ->join('entity_states', 'experiment2entity.entity_id', '=',
                           'entity_states.entity_id')
                 )
                 ->where('attributable_type', EntityState::class)
                 ->where('attributes.name', $attrName)
                 ->join('attribute_values', 'attributes.id', '=',
                     'attribute_values.attribute_id')
                 ->join('entity_states', 'attributes.attributable_id', '=',
                     'entity_states.id')
                 ->join('entities', 'entity_states.entity_id', '=', 'entities.id')
                 ->distinct()
                 ->get()
                 ->groupBy('attributes.name');
    }

    public function getEntityAttributeEntitiesForExperiment($experimentId, $attrName)
    {
        // TBD Not sure what the query is...
    }

    public function getUniqueActivityAttributesForProjectFragment($projectId)
    {
        return DB::table('attributes')
                 ->select('name', 'unit', 'val')
                 ->whereIn(
                     'attributable_id',
                     DB::table('activities')
                       ->select('id')
                       ->where('project_id', $projectId)
                 )
                 ->where('attributable_type', Activity::class)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.attribute_id')
                 ->orderBy('name')
                 ->distinct();
    }

    public function getUniqueActivityAttributesForProject($projectId)
    {
        return $this->getUniqueActivityAttributesForProjectFragment($projectId)->get()->groupBy('name');
    }

    public function getActivityAttributeForProject($projectId, $attrName)
    {
        return DB::table('attributes')
                 ->select('attributes.name', 'attribute_values.unit', 'attribute_values.val', 'attributes.id',
                     'activities.name as object_name', 'activities.id as object_id',
                     DB::raw("'activity' as object_type"))
                 ->whereIn(
                     'attributable_id',
                     DB::table('activities')
                       ->select('id')
                       ->where('project_id', $projectId)
                 )
                 ->where('attributable_type', Activity::class)
                 ->where('attributes.name', $attrName)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.attribute_id')
                 ->join('activities', 'attributes.attributable_id', '=', 'activities.id')
                 ->orderBy('name')
                 ->distinct()
                 ->get()
                 ->groupBy('name');
    }

    public function getUniqueEntityAttributesForProjectFragment($projectId, $category = 'experimental')
    {
        return DB::table('attributes')
                 ->select('name', 'unit', 'val')
                 ->whereIn(
                     'attributable_id',
                     DB::table('entities')
                       ->select('entity_states.id')
                       ->where('project_id', $projectId)
                         ->where('entities.category', $category)
                       ->join('entity_states', 'entities.id', '=', 'entity_states.entity_id')

                 )
                 ->where('attributable_type', EntityState::class)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.attribute_id')
                 ->distinct();
    }

    public function getUniqueEntityAttributesForProject($projectId, $category = 'experimental')
    {
        return $this->getUniqueEntityAttributesForProjectFragment($projectId, $category)->get()->groupBy('name');
    }

    public function getEntityAttributeForProject($projectId, $attrName)
    {
        return DB::table('attributes')
                 ->select('attributes.name', 'attribute_values.unit', 'attribute_values.val', 'attributes.id',
                     'entities.name as object_name', 'entities.id as object_id', DB::raw("'entity' as object_type"))
                 ->whereIn(
                     'attributable_id',
                     DB::table('entities')
                       ->select('entity_states.id')
                       ->where('project_id', $projectId)
                       ->join('entity_states', 'entities.id', '=', 'entity_states.entity_id')

                 )
                 ->where('attributable_type', EntityState::class)
                 ->where('attributes.name', $attrName)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.attribute_id')
                 ->join('entity_states', 'attributes.attributable_id', '=',
                     'entity_states.id')
                 ->join('entities', 'entity_states.entity_id', '=', 'entities.id')
                 ->distinct()
                 ->get()
                 ->groupBy('attributes.name');
    }

    public function getUniqueActivityAttributesForDataset($datasetId)
    {
        return DB::table('attributes')
                 ->select('name', 'unit', 'val')
                 ->whereIn(
                     'attributable_id',
                     DB::table('dataset2entity')
                       ->select('activity2entity.activity_id')
                       ->where('dataset_id', $datasetId)
                       ->join('activity2entity', 'dataset2entity.entity_id', '=', 'activity2entity.entity_id')
                 )
                 ->where('attributable_type', Activity::class)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.attribute_id')
                 ->orderBy('name')
                 ->distinct()
                 ->get()
                 ->groupBy('name');
    }

    public function getUniqueEntityAttributesForDataset($datasetId)
    {
        return DB::table('attributes')
                 ->select('name', 'unit', 'val')
                 ->whereIn(
                     'attributable_id',
                     DB::table('dataset2entity')
                       ->select('entity_states.id')
                       ->where('dataset2entity.dataset_id', $datasetId)
                       ->join('entity_states', 'dataset2entity.entity_id', '=', 'entity_states.entity_id')

                 )
                 ->where('attributable_type', EntityState::class)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.attribute_id')
                 ->distinct()
                 ->get()
                 ->groupBy('name');
    }

    public function getUniqueEntityAttributesForProjects($projectIds)
    {
        return DB::table('attributes')
                 ->select('name', 'unit', 'val')
                 ->whereIn(
                     'attributable_id',
                     DB::table('entities')
                       ->select('entity_states.id')
                       ->whereIn('project_id', $projectIds)
                       ->join('entity_states', 'entities.id', '=', 'entity_states.entity_id')

                 )
                 ->where('attributable_type', EntityState::class)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.attribute_id')
                 ->distinct()
                 ->get()
                 ->groupBy('name');
    }

    public function getUniqueActivityAttributesForProjects($projectIds)
    {
        return DB::table('attributes')
                 ->select('name', 'unit', 'val')
                 ->whereIn(
                     'attributable_id',
                     DB::table('activities')
                       ->select('id')
                       ->whereIn('project_id', $projectIds)
                 )
                 ->where('attributable_type', Activity::class)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.attribute_id')
                 ->orderBy('name')
                 ->distinct()
                 ->get()
                 ->groupBy('name');
    }
}