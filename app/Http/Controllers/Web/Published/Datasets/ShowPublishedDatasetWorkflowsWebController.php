<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;

class ShowPublishedDatasetWorkflowsWebController extends Controller
{
    use ViewsAndDownloads;
    use GoogleDatasetAnnotations;

    public function __invoke($datasetId)
    {
        $this->incrementViews($datasetId);
        $dataset = Dataset::with(['workflows', 'tags'])
                          ->withCount(['views', 'downloads'])
                          ->withCounts()
                          ->findOrFail($datasetId);

        // Datatables does case-insensitive sorting. The database is returning case sensitive, so
        // create a case insensitive list of the workflow items
        $workflows = $dataset->workflows->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
        return view('public.datasets.show', [
            'dataset'      => $dataset,
            'workflows'    => $workflows,
            'dsAnnotation' => $this->jsonLDAnnotations($dataset),
        ]);
    }
}
