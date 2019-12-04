<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;

class ShowPublishedDatasetCommentsWebController extends Controller
{
    public function __invoke($datasetId)
    {
        $dataset = Dataset::with(['comments.owner'])->findOrFail($datasetId);
        $user = auth()->user();

        return view('public.datasets.show', compact('dataset', 'user'));
    }
}
