<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Datasets\ImportDatasetIntoProjectRequest;
use App\Jobs\Datasets\ImportDatasetIntoProjectJob;
use App\Models\Dataset;
use App\Models\Project;
use App\Models\User;

class ImportDatasetIntoProjectWebController extends Controller
{
    public function __invoke(ImportDatasetIntoProjectRequest $request, Project $p, Dataset $dataset)
    {
        $validated = $request->validated();
        abort_unless($this->allowedToImportDataset($dataset, $p), 403);
        ImportDatasetIntoProjectJob::dispatch($dataset, $p, $validated['directory'])->onQueue('globus');
        return redirect(route('projects.show', [$p]));
    }

    private function allowedToImportDataset(Dataset $dataset, Project $project)
    {
        if (!is_null($dataset->published_at)) {
            // Public dataset, so allow it to be imported
            return true;
        }

        // Check that the dataset owner is a member of the project if the dataset being imported
        // is not public.
        return $project->users()->get()->contains(function (User $user) use ($dataset) {
            return $user->id == $dataset->owner_id;
        });
    }
}