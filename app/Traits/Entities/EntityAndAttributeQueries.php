<?php

namespace App\Traits\Entities;

use App\Models\Activity;
use App\Models\EntityState;
use App\Models\Experiment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait EntityAndAttributeQueries
{
    private function getProcessAttrDetails($projectId): Collection
    {
        return $this->getAttrDetails("activity_attrs_by_proj_exp", $projectId);
    }

    private function getSampleAttrDetails($projectId): Collection
    {
        return $this->getAttrDetails("entity_attrs_by_proj_exp", $projectId);
    }

    private function getAttrDetails($table, $projectId): Collection
    {
        $selectRaw = "attribute_name as name, min(cast(attribute_value as real)) as min,".
            "max(cast(attribute_value as real)) as max,".
            "count(distinct attribute_value) as count";

        return DB::table($table)
                 ->selectRaw($selectRaw)
                 ->where("project_id", $projectId)
                 ->whereNotNull("attribute_name")
                 ->groupBy("attribute_name")
                 ->get();
    }

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

    public function getSampleAttributesForExperiment(Experiment $experiment): Collection
    {
        return DB::table('attributes')
                 ->select('name')
                 ->whereIn(
                     'attributable_id',
                     DB::table('entities')
                       ->select('entity_states.id')
                       ->where('project_id', $experiment->project->id)
                       ->where('entities.category', 'experimental')
                       ->whereIn('entities.id',
                           DB::table('experiment2entity')->select('entity_id')->where('experiment_id',
                               $experiment->id))
                       ->join('entity_states', 'entities.id', '=', 'entity_states.entity_id')
                 )
                 ->where('attributable_type', EntityState::class)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.attribute_id')
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

    public function getProcessAttributesForExperiment(Experiment $experiment): Collection
    {
        return DB::table('attributes')
                 ->select('name')
                 ->whereIn(
                     'attributable_id',
                     DB::table('experiment2activity')
                       ->select('activity_id')
                       ->where('experiment_id', $experiment->id)
                 )
                 ->where('attributable_type', Activity::class)
                 ->join('attribute_values', 'attributes.id', '=', 'attribute_values.attribute_id')
                 ->orderBy('name')
                 ->distinct()
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

    public function getExperimentActivityNamesEindexForEntities($experimentId, $entities, $orderByColumn): Collection
    {
        $entityIds = $entities->pluck('id')->toArray();

        return DB::table('experiment2activity')
                 ->select('activities.name', 'activities.eindex')
                 ->where('experiment_id', $experimentId)
                 ->join('activities', 'experiment2activity.activity_id', '=', 'activities.id')
                 ->where('activities.name', '<>', 'Create Samples')
                 ->whereExists(function ($query) use ($entityIds) {
                     $query->select("*")->from('entities')
                           ->join("activity2entity", "entities.id", "=", "activity2entity.entity_id")
                           ->whereColumn("activities.id", "activity2entity.activity_id")
                           ->whereIn("entity_id", $entityIds);
                 })
                 ->distinct()
                 ->orderBy($orderByColumn)
                 ->get();
    }
}