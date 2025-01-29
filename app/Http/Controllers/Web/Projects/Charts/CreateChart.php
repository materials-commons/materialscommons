<?php

namespace App\Http\Controllers\Web\Projects\Charts;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class CreateChart extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project)
    {
        return view('app.projects.charts.create', [
            'project' => $project,
        ]);
    }
}
