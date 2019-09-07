<?php

namespace App\Http\Controllers\Web\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ShowProjectWebController extends Controller
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
        return view('app.projects.show', compact('project'));
    }
}
