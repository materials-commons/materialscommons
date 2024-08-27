<?php

namespace App\Http\Controllers\Web\Published\Datasets\DataDictionary;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Published\Datasets\LoadDatasetContext;
use App\Traits\DataDictionaryQueries;
use App\ViewModels\DataDictionary\ShowPublishedDatasetDataDictionaryViewModel;
use Illuminate\Http\Request;
use function view;

class ShowPublishedDatasetDataDictionaryActivityAttributesWebController extends Controller
{
    use LoadDatasetContext;
    use DataDictionaryQueries;

    public function __invoke(Request $request, $datasetId)
    {
        $this->loadDatasetContext($datasetId);
        $viewModel = (new ShowPublishedDatasetDataDictionaryViewModel())
            ->withDataset($this->dataset)
            ->withUserProjects($this->userProjects)
            ->withHasNotificationsForDataset($this->hasNotificationsForDataset)
            ->withActivityAttributes($this->getUniqueActivityAttributesForDataset($this->dataset->id));
        return view('public.datasets.show', $viewModel);
    }
}
