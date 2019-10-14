<?php

namespace App\Http\Controllers\Web\Datasets\Files;

use App\Actions\Datasets\GetDatasetFilesAction;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;

class ShowDatasetFilesDirectoryWebController extends Controller
{
    public function __invoke(Project $project, Dataset $dataset, $folderId)
    {
        $getDatasetFilesAction = new GetDatasetFilesAction($dataset->file_selection);
        $filesAndDir = $getDatasetFilesAction($project->id, $folderId);
        $directory = $filesAndDir["directory"];
        $files = $filesAndDir["files"];
        $user = auth()->user();
        return view('app.projects.datasets.folders.index', compact('project', 'dataset', 'files', 'directory', 'user'));
    }
}
