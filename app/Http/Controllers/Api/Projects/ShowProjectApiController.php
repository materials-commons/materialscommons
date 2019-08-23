<?php

namespace App\Http\Controllers\Api\Projects;

use App\Http\Controllers\Controller;
use App\Http\Queries\Projects\SingleProjectForUserQuery;
use App\Http\Resources\Projects\ProjectResource;
use Illuminate\Http\Request;

class ShowProjectApiController extends Controller
{
    /**
     * Show details for a specific project.
     *
     * @param  Request  $request
     * @param  SingleProjectForUserQuery  $query
     * @return ProjectResource
     */
    public function __invoke(Request $request, SingleProjectForUserQuery $query)
    {
        $data = $query->get();
        return new ProjectResource($data[0]);
    }
}
