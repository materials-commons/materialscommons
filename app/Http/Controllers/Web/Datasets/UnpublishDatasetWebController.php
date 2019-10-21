<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Datasets\UnpublishDatasetAction;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;

class UnpublishDatasetWebController extends Controller
{
    public function __invoke(UnpublishDatasetAction $unpublishDatasetAction, Project $project, Dataset $dataset)
    {
        $unpublishDatasetAction($dataset);

        return back();
    }
}
