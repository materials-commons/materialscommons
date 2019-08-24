<?php

namespace App\Http\Controllers\Api\Projects;

use App\Actions\Projects\DeleteProjectAction;
use App\Http\Controllers\Controller;
use App\Models\Project;

class DeleteProjectApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Actions\Projects\DeleteProjectAction  $deleteProjectAction
     * @param  \App\Models\Project  $project
     *
     * @return void
     */
    public function __invoke(DeleteProjectAction $deleteProjectAction, Project $project)
    {
        $deleteProjectAction($project);
    }
}
