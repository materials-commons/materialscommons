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
     * @throws \Exception
     */
    public function __invoke(DeleteProjectAction $deleteProjectAction, Project $project)
    {
        abort_unless(auth()->id() === $project->owner_id, 403, "Not project owner");
        abort_unless($project->publishedDatasets()->count() == 0, 403,
            "Cannot delete projects with published datasets");
        $deleteProjectAction($project);
    }
}
