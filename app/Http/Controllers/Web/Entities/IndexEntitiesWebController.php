<?php

namespace App\Http\Controllers\Web\Entities;

use App\Actions\Entities\CreateUsedActivitiesForEntitiesAction;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Entity;
use App\Models\EntityState;
use App\Models\Project;
use App\Models\SavedQuery;
use Illuminate\Support\Facades\DB;

class IndexEntitiesWebController extends Controller
{
    public function __invoke(CreateUsedActivitiesForEntitiesAction $createUsedActivities, Project $project)
    {
        $activities = DB::table('activities')
                        ->select('name')
                        ->where('project_id', $project->id)
                        ->where('name', '<>', 'Create Samples')
                        ->distinct()
                        ->orderBy('name')
//                        ->orderByRaw('case when eindex is null then name else eindex end')
                        ->get();

        $entities = Entity::with(['activities', 'experiments'])
                          ->where('project_id', $project->id)
                          ->get();

        $processAttributes = DB::table('attributes')
                               ->select('name')
                               ->whereIn('attributable_id',
                                   DB::table('activities')
                                     ->select('id')
                                     ->where('project_id', $project->id)
                               )
                               ->where('attributable_type', Activity::class)
                               ->distinct()
                               ->orderBy('name')
                               ->get();

        $sampleAttributes = DB::table('attributes')
                              ->select('name')
                              ->whereIn(
                                  'attributable_id',
                                  DB::table('entities')
                                    ->select('entity_states.id')
                                    ->where('project_id', $project->id)
                                    ->join('entity_states', 'entities.id', '=', 'entity_states.entity_id')

                              )
                              ->where('attributable_type', EntityState::class)
                              ->distinct()
                              ->orderBy('name')
                              ->get();

        $query = "";

        return view('app.projects.entities.index', [
            'project'           => $project,
            'activities'        => $activities,
            'entities'          => $entities,
            'processAttributes' => $processAttributes,
            'sampleAttributes'  => $sampleAttributes,
            'query'             => $query,
            'queries'           => SavedQuery::where('owner_id', auth()->id())
                                             ->where('project_id', $project->id)
                                             ->get(),
            'usedActivities'    => $createUsedActivities->execute($activities, $entities),
        ]);
    }
}
