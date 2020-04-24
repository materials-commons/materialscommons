<?php

namespace App\Http\Controllers\Api\Activities;

use App\Http\Controllers\Controller;
use App\Http\Queries\Activities\AllActivitiesForProjectQuery;
use App\Http\Resources\Activities\ActivityResource;
use Illuminate\Http\Request;

class IndexActivitiesApiController extends Controller
{
    public function __invoke(Request $request, $projectId)
    {
        $query = new AllActivitiesForProjectQuery($projectId, $request);
        return ActivityResource::collection($query->jsonPaginate());
    }
}
