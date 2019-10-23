<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class CreateDatasetWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $experiments = $project->experiments()->get();

        return view('app.projects.datasets.create', compact('project', 'experiments'));
    }
}
