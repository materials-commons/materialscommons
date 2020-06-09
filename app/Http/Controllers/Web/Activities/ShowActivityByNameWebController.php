<?php

namespace App\Http\Controllers\Web\Activities;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Project;

class ShowActivityByNameWebController extends Controller
{
    public function __invoke(Project $project, $name)
    {
        return view('app.projects.activities.show-by-name', [
            'project'    => $project,
            'activities' => Activity::with('entities')
                                    ->where('project_id', $project->id)
                                    ->where('name', $name)
                                    ->get(),
            'name'       => $name,
        ]);
    }
}
