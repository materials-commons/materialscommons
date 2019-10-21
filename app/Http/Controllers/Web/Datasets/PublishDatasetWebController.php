<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Datasets\PublishDatasetAction;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;

class PublishDatasetWebController extends Controller
{
    public function __invoke(PublishDatasetAction $publishDatasetAction, Project $project, Dataset $dataset)
    {
        $publishDatasetAction($dataset);

        return back();
    }
}
