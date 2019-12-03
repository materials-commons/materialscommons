<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;

class ShowPublishedDatasetWebController extends Controller
{
    public function __invoke($datasetId)
    {
        $dataset = Dataset::with('workflows')->findOrFail($datasetId);
        // Datatables does case-insensitive sorting. The database is returning case sensitive, so
        // create a case insensitive list of the workflow items
        $workflows = $dataset->workflows->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
        return view('public.datasets.show', compact('dataset', 'workflows'));
    }
}
