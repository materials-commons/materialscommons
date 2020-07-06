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
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.id')
                 ->orderBy('name')
                 ->distinct()
                 ->get()
                 ->groupBy('name');
    }

    public function getActivityAttributeForExperiment($experimentId, $attrName)
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
                 ->where('name', $attrName)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.id')
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
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.id')
                 ->distinct()
                 ->get()
                 ->groupBy('name');
    }

    public function getEntityAttributeForExperiment($experimentId, $attrName)
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
                 ->where('name', $attrName)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.id')
                 ->distinct()
                 ->get()
                 ->groupBy('name');
    }

    public function getUniqueActivityAttributesForProject($projectId)
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
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.id')
                 ->orderBy('name')
                 ->distinct()
                 ->get()
                 ->groupBy('name');
    }

    public function getActivityAttributeForProject($projectId, $attrName)
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
                 ->where('name', $attrName)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.id')
                 ->orderBy('name')
                 ->distinct()
                 ->get()
                 ->groupBy('name');
    }

    public function getUniqueEntityAttributesForProject($projectId)
    {
        return DB::table('attributes')
                 ->select('name', 'unit', 'val')
                 ->whereIn(
                     'attributable_id',
                     DB::table('entities')
                       ->select('entity_states.id')
                       ->where('project_id', $projectId)
                       ->join('entity_states', 'entities.id', '=', 'entity_states.entity_id')

                 )
                 ->where('attributable_type', EntityState::class)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.id')
                 ->distinct()
                 ->get()
                 ->groupBy('name');
    }

    public function getEntityAttributeForProject($projectId, $attrName)
    {
        return DB::table('attributes')
                 ->select('name', 'unit', 'val')
                 ->whereIn(
                     'attributable_id',
                     DB::table('entities')
                       ->select('entity_states.id')
                       ->where('project_id', $projectId)
                       ->join('entity_states', 'entities.id', '=', 'entity_states.entity_id')

                 )
                 ->where('attributable_type', EntityState::class)
                 ->where('name', $attrName)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.id')
                 ->distinct()
                 ->get()
                 ->groupBy('name');
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
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.id')
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
                       ->where('dataset_id', $datasetId)
                       ->join('entity_states', 'dataset2entity.entity_id', '=', 'entity_states.entity_id')

                 )
                 ->where('attributable_type', EntityState::class)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.id')
                 ->distinct()
                 ->get()
                 ->groupBy('name');
    }
}