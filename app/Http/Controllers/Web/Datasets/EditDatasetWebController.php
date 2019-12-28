<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Datasets\GetDatasetFilesAction;
use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Dataset;
use App\Models\Project;
use App\ViewModels\Datasets\EditDatasetViewModel;

class EditDatasetWebController extends Controller
{
    public function __invoke(Project $project, $datasetId, $folderId = null)
    {
        $dataset = Dataset::with(['communities', 'experiments'])->findOrFail($datasetId);
        $communities = Community::where('public', true)->get();
        $experiments = $project->experiments()->get();
        $getDatasetFilesAction = new GetDatasetFilesAction($dataset->file_selection);
        $filesAndDir = $getDatasetFilesAction($project->id, $folderId ?? '/');
        $directory = $filesAndDir["directory"];
        $files = $filesAndDir["files"];
        $viewModel = new EditDatasetViewModel($project, $dataset, auth()->user());
        $viewModel->withCommunities($communities)
                  ->withExperiments($experiments)
                  ->withFiles($files)
                  ->withDirectory($directory);

        return view('app.projects.datasets.edit', $viewModel);
    }
}
