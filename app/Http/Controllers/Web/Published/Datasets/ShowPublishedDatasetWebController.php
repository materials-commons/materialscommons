<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;

class ShowPublishedDatasetWebController extends Controller
{
    public function __invoke($datasetId)
    {
        $dataset = Dataset::with('workflows')->findOrFail($datasetId);
        return view('public.datasets.show', compact('dataset'));
    }
}
