<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use Illuminate\Http\Request;

class IndexDatasetsWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $datasets = Dataset::with('tags')->where('project_id', $project->id)->get();
        flash("Datasets for project {$project->name} loaded")->success();
        flash("Datasets for project {$project->name} loaded")->error();
        flash("Datasets for project {$project->name} loaded")->warning();
        return view('app.projects.datasets.index', compact('project', 'datasets'));
    }
}
