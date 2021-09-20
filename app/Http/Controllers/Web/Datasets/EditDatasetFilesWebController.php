<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Datasets\GetDatasetFilesAction;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Datasets\Traits\HasExtendedInfo;
use App\Models\Community;
use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;
use App\ViewModels\Datasets\EditOrCreateDataDatasetViewModel;

class EditDatasetFilesWebController extends Controller
{
    use HasExtendedInfo;

    public function __invoke(Project $project, $datasetId, $folderId = null)
    {
        $path = request()->input("path");
        $folder = $this->getFolder($path, $folderId, $project->id);
        $dataset = Dataset::with(['communities', 'experiments', 'tags'])->findOrFail($datasetId);
        $communities = Community::where('public', true)->get();
        $experiments = $project->experiments()->get();
        $getDatasetFilesAction = new GetDatasetFilesAction($dataset->file_selection, $dataset);
        $filesAndDir = $getDatasetFilesAction($project->id, $folder);
        $directory = $filesAndDir["directory"];
        $files = $filesAndDir["files"];
        $viewModel = new EditOrCreateDataDatasetViewModel($project, $dataset, auth()->user());
        $viewModel->withCommunities($communities)
                  ->withExperiments($experiments)
                  ->withDatasetEntities($this->getEntitiesForDataset($dataset))
                  ->withFiles($files)
                  ->withDirectory($directory);

        return view('app.projects.datasets.edit', $viewModel);
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
                      ->where('current', true)
                      ->first();
        return $folder->id;
    }
}
