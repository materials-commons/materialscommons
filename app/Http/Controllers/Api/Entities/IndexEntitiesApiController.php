<?php

namespace App\Http\Controllers\Api\Entities;

use App\Http\Controllers\Controller;
use App\Http\Queries\Entities\AllEntitiesForProjectQuery;
use App\Http\Resources\Entities\EntityResource;
use Illuminate\Http\Request;

class IndexEntitiesApiController extends Controller
{
    public function __invoke(Request $request, $projectId)
    {
        $query = new AllEntitiesForProjectQuery($projectId, $request);
        return EntityResource::collection($query->jsonPaginate());
    }
}
