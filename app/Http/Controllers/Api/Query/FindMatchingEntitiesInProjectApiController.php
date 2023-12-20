<?php

namespace App\Http\Controllers\Api\Query;

use App\Http\Controllers\Controller;
use App\Http\Requests\Query\FindMatchingEntityRequest;
use App\Http\Resources\EntityViews\EntityViewResource;
use App\Models\Entity;
use App\Models\EntityView;
use App\Models\Project;
use Illuminate\Http\Request;

class FindMatchingEntitiesInProjectApiController extends Controller
{
    public function __invoke(FindMatchingEntityRequest $request, Project $project)
    {
        $validated = $request->validated();
        $query = EntityView::query()
                           ->distinct("entity_name")
                           ->select("entity_name")
                           ->where('project_id', $project->id);
        $in = collect();
        $notIn = collect();

        foreach ($validated["activities"] as $activity) {
            switch ($activity["operator"]) {
                case "in":
                    $in->add($activity["name"]);
                    break;
                case "not-in":
                    $notIn->add($activity["name"]);
                    break;
                default:
                    // Should never get here
            }
        }
        if ($in->isNotEmpty()) {
            $query = $query->whereIn("activity_name", $in);
        }

        if ($notIn->isNotEmpty()) {
            $query = $query->whereNotIn("activity_name", $notIn);
        }

        return EntityViewResource::collection($query->get());

        // $matches = EntityView::distinct("entity_name")
//     ->select(["entity_name", "activity_name"])
//     ->whereIn("activity_name", ["SEM", "EBSD", "Optical Microscopy"])
//     ->where("project_id", 809)
//     ->get()
//     ->groupBy("entity_name");

// $c = collect(["a" => 1, "b" => 2, "c" => 3]);

// $c->has(["a", "b", "d"]);

// $matches->filter(function($item) {
//     return $item->keyBy
// })

// $matches->filter(function ($item) {
//     return $item
//         ->transform(function ($i) {
//             return $i->activity_name;
//         })
//         ->has("SEM");
// });

//$matches;

// $first
//     ->keyBy(function ($i) {
//         return $i->activity_name;
//     })
//     ->has("EBSD");

// ->filter(function ($item) {
//     if ($item->count() < 3) {
//         return false;
//     }

// });
    }
}
