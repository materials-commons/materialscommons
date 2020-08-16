<?php

namespace App\Http\Controllers\Api\Projects;

use App\Http\Controllers\Controller;
use App\Http\Resources\Projects\ProjectResource;
use App\Traits\Projects\UserProjects;
use Illuminate\Http\Request;

class IndexProjectsApiController extends Controller
{
    use UserProjects;

    public function __invoke(Request $request)
    {
        $projects = $this->getUserProjects(auth()->id());
        return ProjectResource::collection($projects);
    }
}
