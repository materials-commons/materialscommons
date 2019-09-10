<?php

namespace App\Http\Controllers\Web\Activities;

use App\Http\Controllers\Controller;
use App\Models\Project;

class IndexActivitiesWebController extends Controller
{
    public function __invoke(Project $project)
    {
        return view('app.projects.activities.index', ['project' => $project]);
    }
}
