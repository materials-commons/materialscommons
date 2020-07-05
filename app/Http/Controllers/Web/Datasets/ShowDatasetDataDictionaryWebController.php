<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use App\Traits\DataDictionaryQueries;
use App\ViewModels\DataDictionary\ShowDatasetDataDictionaryViewModel;

class ShowDatasetDataDictionaryWebController extends Controller
{
    use DataDictionaryQueries;

    public function __invoke(Project $project, $datasetId)
    {
        $viewModel = (new ShowDatasetDataDictionaryViewModel())
            ->withProject($project)
            ->withDataset(Dataset::with(['tags'])->find($datasetId))
            ->withActivityAttributes($this->getUniqueActivityAttributesForDataset($datasetId))
            ->withEntityAttributes($this->getUniqueEntityAttributesForDataset($datasetId));
        return view('app.projects.datasets.show', $viewModel);
    }
}
