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
        abort_unless(is_null($dataset->published_at), 400, "Cannot delete a published dataset");
        abort_unless(is_null($dataset->doi), 400, "Cannot delete a dataset that has a DOI assigned");
        $deleteDatasetAction->execute($dataset);
        return redirect(route('projects.datasets.index', [$project]));
    }
}
