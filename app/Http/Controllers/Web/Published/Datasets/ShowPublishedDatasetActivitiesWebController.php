<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;

class ShowPublishedDatasetActivitiesWebController extends Controller
{
    public function __invoke($datasetId)
    {
        $dataset = Dataset::with(['activities', 'tags'])
                          ->withCount(['views', 'downloads'])
                          ->withCounts()
                          ->findOrFail($datasetId);

        return view('public.datasets.show', compact('dataset'));
    }
}
