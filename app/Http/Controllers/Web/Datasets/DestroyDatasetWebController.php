<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;

class DestroyDatasetWebController extends Controller
{
    public function __invoke(Project $project, Dataset $dataset)
    {
        $dataset->delete();
        return redirect(route('projects.datasets.index', compact('project')));
    }
}
