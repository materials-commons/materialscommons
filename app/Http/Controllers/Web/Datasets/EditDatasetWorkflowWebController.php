<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use App\Models\Workflow;

class EditDatasetWorkflowWebController extends Controller
{
    public function __invoke(Project $project, Dataset $dataset, Workflow $workflow)
    {
        return view('app.projects.datasets.workflows.edit', compact('project', 'dataset', 'workflow'));
    }
}
