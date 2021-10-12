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
        if (!is_null($dataset->publish_started_at)) {
            flash("Dataset {$dataset->name} is still being published, cannot unpublish it")->error();
            return redirect(route('projects.datasets.index', [$project]));
        } elseif (is_null($dataset->published_at)) {
            flash("Dataset {$dataset->name} has not been published")->error();
            return redirect(route('projects.datasets.index', [$project]));
        } else {
            $unpublishDatasetAction = new UnpublishDatasetAction();
            $unpublishDatasetAction($dataset, auth()->user());
            flash("Dataset {$dataset->name} successfully unpublished")->success();
        }

        return redirect(route('projects.datasets.show', [$project, $dataset]));
    }
}
