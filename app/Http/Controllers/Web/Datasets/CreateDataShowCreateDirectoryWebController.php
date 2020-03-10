<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;

class CreateDataShowCreateDirectoryWebController extends Controller
{
    public function __invoke(Project $project, Dataset $dataset, File $directory)
    {
        return view('app.projects.datasets.create-directory', compact('project', 'dataset', 'directory'));
    }
}
