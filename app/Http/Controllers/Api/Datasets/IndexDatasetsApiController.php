<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Queries\Datasets\AllDatasetsForProjectQuery;
use App\Http\Resources\Datasets\DatasetResource;
use App\Models\Project;
use Illuminate\Http\Request;

class IndexDatasetsApiController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $query = new AllDatasetsForProjectQuery($project, $request);
        $data = $query->jsonPaginate();
        return DatasetResource::collection($data);
    }
}
