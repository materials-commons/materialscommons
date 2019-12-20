<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Project;

class CreateDatasetWebController extends Controller
{
    public function __invoke(Project $project)
    {
        $communities = Community::where('public', true)->get();
        $experiments = $project->experiments()->get();

        return view('app.projects.datasets.create', compact('project', 'communities', 'experiments'));
    }
}
