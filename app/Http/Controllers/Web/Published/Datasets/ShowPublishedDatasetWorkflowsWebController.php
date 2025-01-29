<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;

class ShowPublishedDatasetWorkflowsWebController extends Controller
{
    use GoogleDatasetAnnotations;
    use LoadDatasetContext;

    public function __invoke($datasetId)
    {
        $this->loadDatasetContext($datasetId);

        // Datatables does case-insensitive sorting. The database is returning case sensitive, so
        // create a case-insensitive list of the workflow items
        $workflows = $this->dataset->workflows->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
        return view('public.datasets.show', [
            'dataset'                    => $this->dataset,
            'userProjects'               => $this->userProjects,
            'workflows'                  => $workflows,
            'hasNotificationsForDataset' => $this->hasNotificationsForDataset,
            'dsAnnotation'               => $this->jsonLDAnnotations($this->dataset),
        ]);
    }
}
