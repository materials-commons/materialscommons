<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;

class CreateDataShowUploadFilesWebController extends Controller
{
    public function __invoke(Project $project, Dataset $dataset, File $directory)
    {
        return view('app.projects.datasets.upload-files', compact('project', 'dataset', 'directory'));
    }
}
