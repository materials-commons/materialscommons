<?php

namespace App\Http\Controllers\Api\Query;

use App\Http\Controllers\Controller;
use App\Http\Requests\Query\FindMatchingEntityRequest;
use App\Models\EntityView;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class FindMatchingEntitiesInProjectApiController extends Controller
{
    public function __invoke(FindMatchingEntityRequest $request, Project $project)
    {
        $validated = $request->validated();

        $innerQuery = DB::table("entity_attrs_by_proj_exp")
                        ->distinct()
                        ->select("entity_name")
                        ->where("project_id", $project->id);

        $in = collect();
        $notIn = collect();

        foreach ($validated["activities"] as $activity) {
            switch ($activity["operator"]) {
                case "in":
                    $in->add($activity["name"]);
                    break;
                case "not-in":
                    // not supported for now. Not sure how to construct the query
                    $notIn->add($activity["name"]);
                    break;
                default:
                    // Should never get here
            }
        }

        if ($in->isNotEmpty()) {
            $innerQuery = $innerQuery->whereIn("activity_name", $in);
        }

        $matches = EntityView::query()
                             ->select(["entity_name", "activity_name"])
                             ->where("project_id", $project->id)
                             ->whereIn("entity_name", $innerQuery)
                             ->get()
                             ->groupBy("entity_name")
                             ->filter(function ($item) use ($in) {
                                 return $item
                                     ->keyBy(function ($i) {
                                         return $i->activity_name;
                                     })
                                     ->has($in->toArray());
                             });

        return ["entities" => $matches->keys()];
    }
}
