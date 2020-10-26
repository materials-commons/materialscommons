<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Datasets\DeleteDatasetAction;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;

class DestroyDatasetWebController extends Controller
{
    public function __invoke(DeleteDatasetAction $deleteDatasetAction, Project $project, Dataset $dataset)
    {
        if (!is_null($dataset->published_at)) {
            flash("Cannot delete a published dataset")->error();
            return redirect(route('projects.datasets.index', [$project]));
        }

        if (!is_null($dataset->doi)) {
            flash("Cannot delete a dataset that has a DOI assigned")->error();
            return redirect(route('projects.datasets.index', [$project]));
        }

        $deleteDatasetAction->execute($dataset);
        return redirect(route('projects.datasets.index', [$project]));
    }
}
