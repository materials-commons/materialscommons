<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Datasets\UpdateDatasetAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Datasets\DatasetRequest;
use App\Models\Dataset;
use App\Models\Project;
use function redirect;
use function route;

class UpdateDatasetWebController extends Controller
{
    public function __invoke(DatasetRequest $request, UpdateDatasetAction $updateDatasetAction, Project $project,
        Dataset $dataset)
    {
        $validated = $request->validated();
        $action = $validated["action"];
        unset($validated["action"]);
        if (!is_null($dataset->published_at)) {
            flash("You have updated a published dataset. The dataset will be republished.")->warning();
        }
        $dataset = $updateDatasetAction($validated, $dataset);
        if ($action === "done") {
            $public = $request->input('public', false);
            if ($public) {
                return redirect(route('public.datasets.show', [$dataset]));
            }
            return redirect(route('projects.datasets.show', [$project, $dataset]));
        } elseif ($action === "save") {
            flash("Dataset updated")->success();
            return redirect(route('projects.datasets.edit', [$project, $dataset]));
        } elseif ($action === "files") {
            return redirect(route('projects.datasets.files.edit', [$project, $dataset]));
        } elseif ($action === "workflow") {
            return redirect(route('projects.datasets.workflows.edit', [$project, $dataset]));
        } elseif ($action === "processes") {
            return redirect(route('projects.datasets.activities.edit', [$project, $dataset]));
        } else {
            return redirect(route('projects.datasets.samples.edit', [$project, $dataset]));
        }
    }
}
