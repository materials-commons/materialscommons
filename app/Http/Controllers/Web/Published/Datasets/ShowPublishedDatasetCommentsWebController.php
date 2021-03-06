<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;

class ShowPublishedDatasetCommentsWebController extends Controller
{
    public function __invoke($datasetId)
    {
        $dataset = Dataset::with(['comments.owner', 'tags'])
                          ->withCount(['views', 'downloads'])
                          ->withCounts()
                          ->findOrFail($datasetId);
        $user = auth()->user();

        return view('public.datasets.show', compact('dataset', 'user'));
    }
}
