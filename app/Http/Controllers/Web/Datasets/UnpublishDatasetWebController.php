<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Datasets\UnpublishDatasetAction;
use App\Actions\Globus\GlobusApi;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;

class UnpublishDatasetWebController extends Controller
{
    public function __invoke(Project $project, Dataset $dataset)
    {
        $unpublishDatasetAction = new UnpublishDatasetAction(GlobusApi::createGlobusApi());
        $unpublishDatasetAction($dataset);

        return back();
    }
}
