<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Datasets\GetDatasetFilesAction;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;
use App\ViewModels\Datasets\EditOrCreateDataDatasetViewModel;

class CreateDataDatasetWebController extends Controller
{
    public function __invoke(Project $project, Dataset $dataset, $folderId = null)
    {
        $path = request()->input("path");
        $folder = $this->getFolder($path, $folderId, $project->id);
        $getDatasetFilesAction = new GetDatasetFilesAction($dataset->file_selection);
        $filesAndDir = $getDatasetFilesAction($project->id, $folder);
        $directory = $filesAndDir["directory"];
        $files = $filesAndDir["files"];
        $viewModel = new EditOrCreateDataDatasetViewModel($project, $dataset, auth()->user());
        $viewModel->withFiles($files)
                  ->withDirectory($directory);
        return view('app.projects.datasets.create-data', $viewModel);
    }

    private function getFolder($path, $folderId, $projectId)
    {
        if ($path === null && $folderId === null) {
            return "/";
        }

        if ($folderId !== null) {
            return $folderId;
        }

        $folder = File::where('project_id', $projectId)
                      ->where('path', $path)
                      ->where('mime_type', 'directory')
                      ->whereNull('deleted_at')
                      ->where('current', true)
                      ->first();
        return $folder->id;
    }
}
