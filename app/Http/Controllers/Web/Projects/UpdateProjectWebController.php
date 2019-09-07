<?php

namespace App\Http\Controllers\Web\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;

class UpdateProjectWebController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Models\Project  $project
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Project $project)
    {
        $attrs = request()->validate([
            'name'            => 'string',
            'description'     => 'string',
            'is_active'       => 'boolean',
            'default_project' => 'boolean',
        ]);

        $project->update($attrs);

        return redirect(route('projects.index'));
    }
}
