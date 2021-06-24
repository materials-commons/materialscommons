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
    private const PROCESS_ATTR_FIELD = 3;
    private const SAMPLE_ATTR_FIELD = 4;
    private const PROCESS_FUNC_TYPE = 5;
    private const SAMPLE_FUNC_TYPE = 6;

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

        $request->flash();

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
        Http::Post("http://localhost:1324/api/reload-project", [
            "project_id" => $project->id,
        ]);

        $statement = $this->buildStatement($data);
        $response = Http::Post("http://localhost:1324/api/execute-query", [
            "project_id"       => $project->id,
            "statement"        => $statement,
            "select_processes" => false,
            "select_samples"   => true,
        ]);
        if (!$response->ok()) {
            return [
                'samples'   => [],
                'processes' => [],
            ];
        }

        return $response->json();
    }

    private function buildStatement($data)
    {
        $processTypes = [];
        if (isset($data['activities'])) {
            $processTypes = $data['activities'];
        }

        $processTypesQuery = $this->buildFromArrayOfSetItems($processTypes, function ($item) {
            return [
                "field_name" => '',
                "field_type" => self::SAMPLE_FUNC_TYPE,
                "value"      => $item,
                "operation"  => 'has-process',
            ];
        });

        $processAttrs = $this->buildAttrsQuery($data['process_attrs'], function ($item) {
            return [
                "field_name" => $item["name"],
                "field_type" => self::PROCESS_ATTR_FIELD,
                "value"      => $item["value"],
                "operation"  => $item["operator"],
            ];
        });

        $sampleAttrs = $this->buildAttrsQuery($data['sample_attrs'], function ($item) {
            return [
                "field_name" => $item["name"],
                "field_type" => self::SAMPLE_ATTR_FIELD,
                "value"      => $item["value"],
                "operation"  => $item["operator"],
            ];
        });

        $numberOfQueries = 0;
        if (!is_null($processTypesQuery)) {
            $numberOfQueries++;
        }

        if (!is_null($processAttrs)) {
            $numberOfQueries++;
        }

        if (!is_null($sampleAttrs)) {
            $numberOfQueries++;
        }

        if ($numberOfQueries == 0) {
            // no queries
            return null;
        }

        if ($numberOfQueries == 1) {
            return $this->firstNotNull($processTypesQuery, $processAttrs, $sampleAttrs);
        }

        // Two or more not null
        $toplevelQuery = [
            'left'  => null,
            'right' => null,
            'and'   => true,
        ];

        if ($numberOfQueries == 2) {
            $toplevelQuery['left'] = $this->firstNotNull($processTypesQuery, $processAttrs, $sampleAttrs);
            $toplevelQuery['right'] = $this->secondNotNull($processTypesQuery, $processAttrs, $sampleAttrs);
            return $toplevelQuery;
        }

        // There are 3 parts to the query
        $toplevelQuery['left'] = $processTypesQuery;
        $toplevelQuery['right'] = [
            'left'  => $processAttrs,
            'right' => $sampleAttrs,
            'and'   => true,
        ];

        return $toplevelQuery;
    }

    private function buildFromArrayOfSetItems($items, $buildMatchFn)
    {
        if (sizeof($items) == 0) {
            return null;
        }

        if (sizeof($items) == 1) {
            return $buildMatchFn($items[0]);
        }

        $itemsQuery = [
            'left'  => [],
            'right' => [],
            'and'   => true,
        ];

        $current = &$itemsQuery;
        $termsCount = 0;
        for ($i = 0; $i < sizeof($items); $i++) {
            $current['left'] = $buildMatchFn($items[$i]);
            $termsCount++;
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
            if (sizeof($items) - 2 == $i) {
                $current['right'] = $buildMatchFn($items[$i + 1]);
                $termsCount++;
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
            $current = &$current['right'];
        }

        if ($termsCount == 0) {
            return null;
        }

        return $itemsQuery;
    }

    private function buildAttrsQuery($attrs, $buildMatchFn)
    {
        $setAttrs = [];
        foreach ($attrs as $attr) {
            if (isset($attr['name'])) {
                array_push($setAttrs, $attr);
            }
        }

        if (sizeof($setAttrs) == 0) {
            return null;
        }

        if (sizeof($setAttrs) == 1) {
            return $buildMatchFn($setAttrs[0]);
        }

        if (sizeof($setAttrs) == 2) {
            return [
                'left'  => $buildMatchFn($setAttrs[0]),
                'right' => $buildMatchFn($setAttrs[1]),
                'and'   => true,
            ];
        }

        // More than two items
        return $this->buildFromArrayOfSetItems($setAttrs, $buildMatchFn);
    }

    private function firstNotNull($first, $second, $third)
    {
        if (!is_null($first)) {
            return $first;
        }

        if (!is_null($second)) {
            return $second;
        }

        return $third;
    }

    private function secondNotNull($first, $second, $third)
    {
        // if $first is not null then the second not null is either $second or $third
        if (!is_null($first)) {
            if (!is_null($second)) {
                return $second;
            }
            return $third;
        }

        // if $first is null, then by definition the second not null is $third
        return $third;
    }

    private function filterEntitiesUsingQueryResults(Collection $entities, $queryResults): Collection
    {
        $samples = collect($queryResults['samples']);
        return $entities->filter(function (Entity $entity) use ($samples) {
            return $samples->where('id', $entity->id)->count() != 0;
        });
    }
}
