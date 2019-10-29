<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;

class ShowPublishedDatasetWebController extends Controller
{
    public function __invoke(Dataset $dataset)
    {
        return view('public.datasets.show', compact('dataset'));
    }
}
