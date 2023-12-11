<?php

namespace App\Http\Controllers\Web\Activities\Computations;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Project;
use Illuminate\Http\Request;
use function view;

class IndexComputationsWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $calculations = Activity::with(['owner'])
                                ->where('project_id', $project->id)
                                ->where('category', 'computational')
                                ->get();
        return view('app.projects.activities.computations.index', [
            'project'    => $project,
            'activities' => $calculations,
        ]);
    }
}
