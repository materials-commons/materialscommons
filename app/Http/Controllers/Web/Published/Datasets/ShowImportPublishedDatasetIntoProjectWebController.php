<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;

class ShowImportPublishedDatasetIntoProjectWebController extends Controller
{
    public function __invoke(Dataset $dataset)
    {
        return view('public.datasets.import-dataset', [
            'dataset'  => $dataset,
            'projects' => auth()->user()->projects(),
        ]);
    }
}
