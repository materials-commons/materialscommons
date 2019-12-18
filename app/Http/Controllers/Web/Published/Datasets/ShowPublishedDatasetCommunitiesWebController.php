<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;

class ShowPublishedDatasetCommunitiesWebController extends Controller
{
    public function __invoke($datasetId)
    {
        $dataset = Dataset::with('publishedCommunities')->findOrFail($datasetId);

        return view('public.datasets.show', compact('dataset'));
    }
}
