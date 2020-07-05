<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;

class ShowDatasetWorkflowsWebController extends Controller
{
    public function __invoke(Project $project, $datasetId)
    {
        $dataset = Dataset::with(['tags', 'workflows'])->findOrFail($datasetId);
        $workflows = $dataset->workflows->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
        return view('app.projects.datasets.show', compact('project', 'dataset', 'workflows'));
    }
}