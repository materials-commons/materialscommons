<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;

class DeleteDatasetWebController extends Controller
{
    public function __invoke(Project $project, Dataset $dataset)
    {
        $destroyRoute = route('projects.datasets.destroy', [$project, $dataset]);
        $item = $dataset;
        return view('common.delete-item', compact('project', 'item', 'destroyRoute'));
    }
}
