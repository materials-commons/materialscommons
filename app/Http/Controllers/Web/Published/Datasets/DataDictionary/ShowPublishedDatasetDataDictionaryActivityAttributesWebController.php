<?php

namespace App\Http\Controllers\Web\Published\Datasets\DataDictionary;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Published\Datasets\LoadDatasetContext;
use Illuminate\Http\Request;
use function view;

class ShowPublishedDatasetDataDictionaryActivityAttributesWebController extends Controller
{
    use LoadDatasetContext;

    public function __invoke(Request $request, $datasetId)
    {
        $this->loadDatasetContext($datasetId);
        return view('public.datasets.show', [
            'dataset'                    => $this->dataset,
            'userProjects'               => $this->userProjects,
            'hasNotificationsForDataset' => $this->hasNotificationsForDataset,
        ]);
    }
}
