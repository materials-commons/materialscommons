<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Datasets\GetDatasetFilesAction;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Datasets\Traits\HasExtendedInfo;
use App\Models\Dataset;
use App\Models\Project;
use App\ViewModels\Datasets\ShowDatasetOverviewViewModel;
use Illuminate\Http\Request;

class ShowDatasetFilesWebController extends Controller
{
    use HasExtendedInfo;

    public function __invoke(Request $request, Project $project, $datasetId)
    {
        $dataset = Dataset::with('tags')->find($datasetId);
        $getDatasetFilesAction = new GetDatasetFilesAction($dataset->file_selection, $dataset);
        $filesAndDir = $getDatasetFilesAction($project->id, '/');
        $showDatasetOverviewViewModel = (new ShowDatasetOverviewViewModel())
            ->withProject($project)
            ->withDataset($dataset)
            ->withEditRoute(route('projects.datasets.files.edit', [$project, $dataset]))
            ->withFiles($filesAndDir["files"])
            ->withDirectory($filesAndDir["directory"])
            ->withEntities($this->getEntitiesForDataset($dataset));
        return view('app.projects.datasets.show', $showDatasetOverviewViewModel);
    }
}
