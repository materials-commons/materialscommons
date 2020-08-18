<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;

class ShowPublishedDatasetWebController extends Controller
{
    public function __invoke($datasetId)
    {
        return redirect(route('public.datasets.overview.show', [$datasetId]));
    }
}
