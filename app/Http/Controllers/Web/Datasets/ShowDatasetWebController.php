<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use Illuminate\Http\Request;

class ShowDatasetWebController extends Controller
{
    public function __invoke(Request $request, Project $project, Dataset $dataset)
    {
        return view('app.projects.datasets.show', compact('project', 'dataset'));
    }
}
