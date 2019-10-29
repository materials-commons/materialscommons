<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;

class ShowPublishedDatasetFilesWebController extends Controller
{
    public function __invoke($datasetId)
    {
        $dataset = Dataset::with('files')->findOrFail($datasetId);

        return view('public.datasets.show', compact('dataset'));
    }
}
