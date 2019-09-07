<?php

namespace App\Http\Controllers\Web\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;

class DeleteProjectWebController extends Controller
{
    /**
     * Delete project.
     *
     * @param  \App\Models\Project  $project
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function __invoke(Project $project)
    {
        $project->delete();

        return redirect(route('projects.index'));
    }
}
