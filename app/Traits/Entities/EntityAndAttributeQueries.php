<?php

namespace App\Traits\Entities;

use App\Models\Activity;
use App\Models\EntityState;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use function collect;

trait EntityAndAttributeQueries
{
    public function getSampleAttributes($projectId): Collection
    {
        return DB::table('attributes')
                 ->select('name')
                 ->whereIn(
                     'attributable_id',
                     DB::table('entities')
                       ->select('entity_states.id')
                       ->where('project_id', $projectId)
                       ->join('entity_states', 'entities.id', '=', 'entity_states.entity_id')

                 )
                 ->where('attributable_type', EntityState::class)
                 ->distinct()
                 ->orderBy('name')
                 ->get();
    }

    public function getSampleAttributesGroupedByProcess($projectId): Collection
    {
        return DB::table('attributes')
                 ->select('attributes.name', 'activities.name as activity')
                 ->whereIn(
                     'attributable_id',
                     DB::table('entities')
                       ->select('entity_states.id')
                       ->where('project_id', $projectId)
                       ->join('entity_states', 'entities.id', '=', 'entity_states.entity_id')
                 )
                 ->where('attributable_type', EntityState::class)
                 ->join("activity2entity_state", "activity2entity_state.entity_state_id", "=",
                     "attributes.attributable_id")
                 ->join("activities", "activities.id", "=", "activity2entity_state.activity_id")
                 ->distinct()
                 ->orderBy('activity')
                 ->get()
                 ->groupBy('activity');
    }

    public function getProcessAttributes($projectId): Collection
    {
        return DB::table('attributes')
                 ->select('name')
                 ->whereIn('attributable_id',
                     DB::table('activities')
                       ->select('id')
                       ->where('project_id', $projectId)
                 )
                 ->where('attributable_type', Activity::class)
                 ->distinct()
                 ->orderBy('name')
                 ->get();
    }

    public function getProcessAttributesGroupedByProcess($projectId): Collection
    {
        return DB::table('attributes')
                 ->select('attributes.name', "activities.name as activity")
                 ->whereIn(
                     'attributable_id',
                     DB::table('activities')
                       ->select('id')
                       ->where('project_id', $projectId)
                 )
                 ->where('attributable_type', Activity::class)
                 ->join("activities", "activities.id", "=", "attributes.attributable_id")
                 ->distinct()
                 ->orderBy('activity')
                 ->get()
                 ->groupBy('activity');
    }

    public function getProjectExperimentalActivities($projectId): Collection
    {
        return DB::table('activities')
                 ->select('name')
                 ->where('project_id', $projectId)
                 ->whereNull("category")
                 ->where('name', '<>', 'Create Samples')
                 ->distinct()
                 ->orderBy('name')
                 ->get();
    }

    public function getProjectComputationalActivities($projectId): Collection
    {
        return DB::table('activities')
                 ->select('name')
                 ->where('project_id', $projectId)
                 ->where("category", "computational")
                 ->where('name', '<>', 'Create Samples')
                 ->distinct()
                 ->orderBy('name')
                 ->get();
    }

    public function getProjectActivityNamesForEntities($projectId, $entities): Collection
    {
        $entityIds = $entities->pluck('id')->toArray();
        return DB::table("activities")
                 ->select("name")
                 ->where("project_id", $projectId)
                 ->where("name", "<>", "Create Samples")
                 ->whereExists(function ($query) use ($entityIds) {
                     $query->select("*")->from('entities')
                           ->join("activity2entity", "entities.id", "=", "activity2entity.entity_id")
                           ->whereColumn("activities.id", "activity2entity.activity_id")
                           ->whereIn("entity_id", $entityIds);
                 })
                 ->distinct()
                 ->orderBy("name")
                 ->get();
    }
}