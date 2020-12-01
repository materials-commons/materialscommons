<?php

namespace App\Http\Controllers\Api\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Datasets\ImportDatasetIntoProjectRequest;
use App\Jobs\Datasets\ImportDatasetIntoProjectJob;
use App\Models\Dataset;
use App\Models\Project;
use App\Models\User;

class ImportDatasetIntoProjectApiController extends Controller
{
    public function __invoke(ImportDatasetIntoProjectRequest $request, Project $p, Dataset $dataset)
    {
        $p->load('team');
        $validated = $request->validated();
        abort_unless($this->allowedToImportDataset($dataset, $p), 403,
            "You don't have access to import this dataset into the project");
        ImportDatasetIntoProjectJob::dispatch($dataset, $p, $validated['directory'])->onQueue('globus');
    }

    private function allowedToImportDataset(Dataset $dataset, Project $project)
    {
        if (!is_null($dataset->published_at)) {
            // Public dataset, so allow it to be imported
            return true;
        }

        // Check that the dataset owner is a member of the project if the dataset being imported
        // is not public.

        if ($this->isMemberOfProject($project, $dataset)) {
            return true;
        }

        return $this->isAdminOfProject($project, $dataset);
    }

    private function isMemberOfProject(Project $project, Dataset $dataset)
    {
        return $project->team->members->contains(function (User $user) use ($dataset) {
            return $user->id == $dataset->owner_id;
        });
    }

    private function isAdminOfProject(Project $project, Dataset $dataset)
    {
        return $project->team->admins->contains(function (User $user) use ($dataset) {
            return $user->id == $dataset->owner_id;
        });
    }
}
