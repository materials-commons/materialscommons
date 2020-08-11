<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;

class ShowDatasetWebController extends Controller
{
    public function __invoke($projectId, $datasetId)
    {
        return redirect(route('projects.datasets.show.overview', [$projectId, $datasetId]));
    }
}
