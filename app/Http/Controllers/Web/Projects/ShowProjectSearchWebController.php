<?php

namespace App\Http\Controllers\Web\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ShowProjectSearchWebController extends Controller
{
    public function __invoke(Project $project)
    {
        return view('app.search.project', [
            'project' => $project,
        ]);
    }
}
