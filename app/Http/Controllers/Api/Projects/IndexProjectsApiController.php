<?php

namespace App\Http\Controllers\Api\Projects;

use App\Http\Controllers\Controller;
use App\Http\Queries\Projects\AllProjectsForUserQuery;
use App\Http\Resources\Projects\ProjectResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexProjectsApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  Request  $request
     * @param  AllProjectsForUserQuery  $query
     * @return AnonymousResourceCollection
     */
    public function __invoke(Request $request, AllProjectsForUserQuery $query)
    {
        $data = $query->jsonPaginate();
        return ProjectResource::collection($data);
    }
}
