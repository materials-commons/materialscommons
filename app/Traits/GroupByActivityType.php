<?php

namespace App\Traits;

use App\Models\Activity;
use App\Models\EntityState;
use Illuminate\Support\Facades\DB;

trait GroupByActivityType
{
    private function getActivityTypes($activityIds)
    {
        return DB::table('activities')
                 ->select('name', DB::raw('count(*) as count'))
                 ->whereIn('id', $activityIds)
                 ->groupBy('name')
                 ->orderBy('name')
                 ->get();
    }

    private function getAttributesByActivityType($activityIds)
    {
        return DB::table('activities')
                 ->select('activities.name', 'attributes.name as attr_name', 'unit', 'val', 'attributes.eindex')
                 ->whereIn('activities.id', $activityIds)
                 ->join('attributes', function ($join) {
                     $join->on('attributes.attributable_id', '=', 'activities.id')
                          ->where('attributable_type', Activity::class);
                 })
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.attribute_id')
                 ->distinct()
                 ->orderBy('attributes.eindex')
                 ->get()
                 ->map(function ($attr) {
                     $attr->val = json_decode($attr->val, true);
                     return $attr;
                 })
                 ->groupBy('name');
    }

    private function getFilesByActivityType($activityIds)
    {
        return DB::table('activities')
                 ->select('activities.name', 'files.name as fname', 'files.id as fid', 'files.mime_type as mime_type')
                 ->whereIn('activities.id', $activityIds)
                 ->join('activity2file', 'activity2file.activity_id', '=', 'activities.id')
                 ->join('files', 'activity2file.file_id', '=', 'files.id')
                 ->distinct()
                 ->get()
                 ->groupBy('name');
    }

    private function getMeasurementsByActivityType($activityIds)
    {
        return DB::table('activities')
                 ->select('activities.name', 'attributes.name as attr_name', 'unit', 'val', 'attributes.eindex')
                 ->whereIn('activities.id', $activityIds)
                 ->join('activity2entity_state', 'activities.id', '=',
                     'activity2entity_state.activity_id')
                 ->join('attributes', function ($join) {
                     $join->on('attributes.attributable_id', '=',
                         'activity2entity_state.entity_state_id')
                          ->where('attributable_type', EntityState::class);
                 })
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.attribute_id')
                 ->orderBy('attributes.eindex')
                 ->distinct()
                 ->get()
                 ->map(function ($attr) {
                     $attr->val = json_decode($attr->val, true);
                     return $attr;
                 })
                 ->groupBy('name');
    }
}

