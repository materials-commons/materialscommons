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
        if (blank($dataset->description)) {
            flash("Cannot publish Dataset {$dataset->name}: It's missing a description")->error();
            return redirect(route('projects.datasets.index', [$project]));
        }

        if (!$dataset->hasSelectedFiles()) {
            flash("Cannot publish Dataset {$dataset->name}: It has not files")->error();
            return redirect(route('projects.datasets.index', [$project]));
        }

        $publishDatasetAction($dataset);
        flash("Dataset {$dataset->name} successfully published")->success();
        return redirect(route('projects.datasets.index', [$project]));
    }
}
