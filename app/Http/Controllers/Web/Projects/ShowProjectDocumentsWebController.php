<?php

namespace App\Http\Controllers\Web\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ShowProjectDocumentsWebController extends Controller
{
    public function __invoke(Project $project)
    {
        return view('app.projects.show', ['project' => $project]);
    }
}
