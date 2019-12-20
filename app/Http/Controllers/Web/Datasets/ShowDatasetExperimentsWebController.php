<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use Illuminate\Http\Request;

class ShowDatasetExperimentsWebController extends Controller
{
    public function __invoke(Request $request, Project $project, $datasetId)
    {
        $dataset = Dataset::with('experiments')->find($datasetId);
        return view('app.projects.datasets.show', compact('project', 'dataset'));
    }
}
