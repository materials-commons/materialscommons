<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use function view;

class ShowPublishedDatasetCommentsWebController extends Controller
{
    use LoadDatasetContext;

    public function __invoke($datasetId)
    {
        $this->loadDatasetContext($datasetId);

        return view('public.datasets.show', [
            'dataset'                    => $this->dataset,
            'userProjects'               => $this->userProjects,
            'user'                       => $this->user,
            'hasNotificationsForDataset' => $this->hasNotificationsForDataset,
        ]);
    }
}
