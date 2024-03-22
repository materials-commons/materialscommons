<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Jobs\Datasets\RefreshPublishedDatasetJob;
use App\Models\Dataset;
use App\Models\Project;
use Illuminate\Http\Request;

class RefreshPublishedDatasetWebController extends Controller
{
    public function __invoke(Request $request, Project $project, Dataset $dataset)
    {
        $dataset->load('owner');
        RefreshPublishedDatasetJob::dispatch($dataset, $dataset->owner)->onQueue('globus');
        flash("Dataset {$dataset->name} refresh started in background")->success();
        return redirect(route('projects.datasets.show', [$project, $dataset]));
    }
}
