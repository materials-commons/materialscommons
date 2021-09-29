<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Datasets\PublishDatasetAction2;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;

class PublishDatasetWebController extends Controller
{
    public function __invoke(Project $project, Dataset $dataset)
    {
        if (blank($dataset->description)) {
            flash("Cannot publish Dataset {$dataset->name}: It's missing a description")->error();
            return redirect(route('projects.datasets.show', [$project, $dataset]));
        }

        if (!$dataset->hasSelectedFiles()) {
            flash("Cannot publish Dataset {$dataset->name}: It has not files")->error();
            return redirect(route('projects.datasets.show', [$project, $dataset]));
        }

        $publishDatasetAction = new PublishDatasetAction2();
        $publishDatasetAction->execute($dataset, auth()->user());

        return redirect(route('projects.datasets.published-message', [$project, $dataset]));
    }
}
