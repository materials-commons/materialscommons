<?php

namespace App\Http\Controllers\Api\Experiments;

use App\Http\Controllers\Controller;
use App\Http\Queries\Experiments\AllExperimentsForProjectQuery;
use App\Http\Resources\Experiments\ExperimentResource;
use Illuminate\Http\Request;

class IndexExperimentsApiController extends Controller
{
    public function __invoke(Request $request, $projectId)
    {
        $query = new AllExperimentsForProjectQuery($projectId, $request);
        return ExperimentResource::collection($query->jsonPaginate());
    }
}
