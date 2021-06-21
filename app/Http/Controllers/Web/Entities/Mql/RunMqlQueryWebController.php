<?php

namespace App\Http\Controllers\Web\Entities\Mql;

use App\Actions\Entities\CreateUsedActivitiesForEntitiesAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mql\MqlSelectionRequest;
use App\Models\Activity;
use App\Models\Entity;
use App\Models\EntityState;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class RunMqlQueryWebController extends Controller
{
    public function __invoke(MqlSelectionRequest $request, CreateUsedActivitiesForEntitiesAction $createUsedActivities,
        Project $project)
    {
        $validated = $request->validated();

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
                          ->limit(2)
                          ->get();

        $processAttributes = DB::table('attributes')
                               ->select('name')
                               ->whereIn('attributable_id',
                                   DB::table('entities')
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

        $filters = "";

        $queryResults = $this->runQuery($validated, $project);
        $entities = $this->filterEntitiesUsingQueryResults($entities, $queryResults);

        return view('app.projects.entities.index', [
            'project'           => $project,
            'activities'        => $activities,
            'entities'          => $entities,
            'processAttributes' => $processAttributes,
            'sampleAttributes'  => $sampleAttributes,
            'filters'           => $filters,
            'usedActivities'    => $createUsedActivities->execute($activities, $entities),
        ]);
    }

    private function runQuery($data, Project $project)
    {
        $statement = $this->buildStatement($data);
        return Http::Post("http://localhost:1324/api/execute-query", [
            "project_id"       => $project->id,
            "statement"        => $statement,
            "select_processes" => false,
            "select_samples"   => true,
        ])->json();
    }

    private function buildStatement($data)
    {
        $processTypes = [];
        if (isset($data['activites'])) {
            $processTypes = $data['activities'];
        }

        $processTypesQuery = $this->buildProcessTypesQuery($processTypes);
        $processAttrs = [];
        $sampleAttrs = [];
    }

    private function buildProcessTypesQuery($processTypes)
    {
        if (sizeof($processTypes) == 0) {
            return null;
        }

        if (sizeof($processTypes) == 1) {
            return [
                "field_name" => '',
                "field_type" => 'sample-function',
                "value"      => $processTypes[0],
                "operation"  => 'has-process',
            ];
        }

        $processTypesQuery = [
            'left'  => [],
            'right' => [],
            'and'   => true,
        ];

        $current = $processTypesQuery;
        for ($i = 0; $i < sizeof($processTypes); $i++) {
            $current['left'] = [
                "field_name" => '',
                "field_type" => 'sample-function',
                "value"      => $processTypes[$i],
                "operation"  => 'has-process',
            ];
            // if we are on the second to last entry then right will also be a match statement.
            // To simplify tracking this we just set up the right side here and then exit the
            // loop.
            // Example:
            // $processTypes = ['A', 'B', 'C']
            // sizeof($processTypes) = 3
            // Thus if we are on index 1 ('B'), then we know that the right hand side will
            // be a match statement, rather than another and statement. Thus if
            // sizeof($processTypes) - 2 == $i, then $i = 1, and $i+1 = 2, which is the third
            // entry in $processTypes and we can set this up as match statement for the right
            // side of the our AND statement.
            if (sizeof($processTypes) - 2 == $i) {
                $current['right'] = [
                    "field_name" => '',
                    "field_type" => 'sample-function',
                    "value"      => $processTypes[$i + 1],
                    "operation"  => 'has-process',
                ];
                break;
            }

            // If we are here then we are setting the right side to another AND statement. Using the
            // example above ['A', 'B', 'C'], assuming we have just processed 'A', we have the
            // following setup:
            // [
            //    'left' => [
            //            "field_name" => '',
            //            "field_type" => 'sample-function',
            //            "value"      => 'A',
            //            "operation"  => 'has-process',
            //    ],
            //    'right' => [
            //             "left"  => [],
            //             "right" => [],
            //             "and"   => true
            //    ],
            //    'and' => true
            // ]
            $current['right'] = [
                'left'  => [],
                'right' => [],
                'and'   => true,
            ];

            // Point $current at the newly created AND statement
            $current = $current['right'];
        }
        return $processTypesQuery;
    }

    private function filterEntitiesUsingQueryResults(Collection $entities, $queryResults)
    {
    }
}
