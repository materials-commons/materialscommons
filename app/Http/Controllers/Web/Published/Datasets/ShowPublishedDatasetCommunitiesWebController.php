<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;

class ShowPublishedDatasetCommunitiesWebController extends Controller
{
    public function __invoke($datasetId)
    {
        $dataset = Dataset::with('publishedCommunities')->withCount(['views', 'downloads'])->findOrFail($datasetId);

        return view('public.datasets.show', compact('dataset'));
    }
}
