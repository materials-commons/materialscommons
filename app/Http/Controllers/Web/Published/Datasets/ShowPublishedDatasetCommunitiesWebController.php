<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use function view;

class ShowPublishedDatasetCommunitiesWebController extends Controller
{
    use LoadDatasetContext;

    public function __invoke($datasetId)
    {
        $this->loadDatasetContext($datasetId);

        return view('public.datasets.show', [
            'userProjects'               => $this->userProjects,
            'dataset'                    => $this->dataset,
            'hasNotificationsForDataset' => $this->hasNotificationsForDataset,
        ]);
    }
}
