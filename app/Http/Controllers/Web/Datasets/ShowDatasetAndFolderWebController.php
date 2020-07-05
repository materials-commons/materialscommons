<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Datasets\GetDatasetFilesAction;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;

class ShowDatasetAndFolderWebController extends Controller
{
    public function __invoke(Project $project, $datasetId, $folderId)
    {
        $dataset = Dataset::with('tags')->findOrFail($datasetId);
        $getDatasetFilesAction = new GetDatasetFilesAction($dataset->file_selection);
        $filesAndDir = $getDatasetFilesAction($project->id, $folderId);
        $directory = $filesAndDir["directory"];
        $files = $filesAndDir["files"];
        return view('app.projects.datasets.show', compact('project', 'dataset', 'directory', 'files'));
    }
}
