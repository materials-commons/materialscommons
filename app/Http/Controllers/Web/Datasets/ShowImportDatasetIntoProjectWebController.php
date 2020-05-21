<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;

class ShowImportDatasetIntoProjectWebController extends Controller
{
    public function __invoke(Dataset $dataset, Project $project)
    {
        return view('app.projects.datasets.import-dataset', [
            'dataset'  => $dataset,
            'projects' => auth()->user()->projects(),
            'project'  => $project,
        ]);
    }
}
