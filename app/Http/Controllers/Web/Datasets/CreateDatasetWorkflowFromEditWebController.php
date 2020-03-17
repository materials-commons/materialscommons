<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;

class CreateDatasetWorkflowFromEditWebController extends Controller
{
    public function __invoke(Project $project, Dataset $dataset)
    {
        return view('app.projects.workflows.create-workflow-from-edit', compact('project', 'dataset'));
    }
}
