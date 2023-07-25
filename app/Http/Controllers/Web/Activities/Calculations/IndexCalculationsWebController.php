<?php

namespace App\Http\Controllers\Web\Activities\Calculations;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Project;
use Illuminate\Http\Request;
use function view;

class IndexCalculationsWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $calculations = Activity::with(['owner'])
                                ->where('project_id', $project->id)
                                ->where('category', 'calculation')
                                ->get();
        return view('app.projects.activities.calculations.index', [
            'project'    => $project,
            'activities' => $calculations,
        ]);
    }
}
