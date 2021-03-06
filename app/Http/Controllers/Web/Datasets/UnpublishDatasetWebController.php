<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Datasets\UnpublishDatasetAction;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;

class UnpublishDatasetWebController extends Controller
{
    public function __invoke(Project $project, Dataset $dataset)
    {
        $unpublishDatasetAction = new UnpublishDatasetAction();
        $unpublishDatasetAction($dataset);
        flash("Dataset {$dataset->name} successfully unpublished")->success();
        return redirect(route('projects.datasets.index', [$project]));
    }
}
