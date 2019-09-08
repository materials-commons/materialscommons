<?php

namespace App\Http\Controllers\Web\Projects;

use App\Actions\Projects\DeleteProjectAction;
use App\Http\Controllers\Controller;
use App\Models\Project;

class DeleteProjectWebController extends Controller
{
    /**
     * @param  \App\Actions\Projects\DeleteProjectAction  $deleteProjectAction
     * @param  \App\Models\Project  $project
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function __invoke(DeleteProjectAction $deleteProjectAction, Project $project)
    {
        $deleteProjectAction($project);

        return redirect(route('projects.index'));
    }
}
