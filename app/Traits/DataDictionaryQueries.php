<?php

namespace App\Traits;

use App\Models\Activity;
use App\Models\EntityState;
use Illuminate\Support\Facades\DB;

trait DataDictionaryQueries
{
    private function getUniqueActivityAttributesForExperiment($experimentId)
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

    private function getActivityAttributeForExperiment($experimentId, $attrName)
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

    private function getUniqueEntityAttributesForExperiment($experimentId)
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

    private function getEntityAttributeForExperiment($experimentId, $attrName)
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

    private function getUniqueActivityAttributesForProject($projectId)
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

    private function getActivityAttributeForProject($projectId, $attrName)
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

    private function getUniqueEntityAttributesForProject($projectId)
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

    private function getEntityAttributeForProject($projectId, $attrName)
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
}