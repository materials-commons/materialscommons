<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;

class EditDatasetWebController extends Controller
{
    public function __invoke(Project $project, Dataset $dataset)
    {
        return view('app.projects.datasets.edit', compact('project', 'dataset'));
    }
}
