<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Datasets\GetDatasetFilesAction;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use Illuminate\Http\Request;

class ShowDatasetWebController extends Controller
{
    public function __invoke(Request $request, Project $project, $datasetId)
    {
        $dataset = Dataset::with('experiments', 'tags')->find($datasetId);
        $getDatasetFilesAction = new GetDatasetFilesAction($dataset->file_selection);
        $filesAndDir = $getDatasetFilesAction($project->id, '/');
        $directory = $filesAndDir["directory"];
        $files = $filesAndDir["files"];
        return view('app.projects.datasets.show', compact('project', 'dataset', 'directory', 'files'));
    }
}
