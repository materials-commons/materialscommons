<?php

namespace App\Http\Controllers\Api\Projects;

use App\Http\Controllers\Controller;
use App\Http\Queries\Projects\SingleProjectQuery;
use App\Http\Resources\Projects\ProjectResource;

class ShowProjectApiController extends Controller
{
    /**
     * Show details for a specific project.
     *
     * @param  SingleProjectQuery  $query
     *
     * @return ProjectResource
     */
    public function __invoke(SingleProjectQuery $query)
    {
        $data = $query->get();
        return new ProjectResource($data[0]);
    }
}
