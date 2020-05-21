<?php

namespace App\Http\Controllers\Web\Datasets;

use App\Actions\Projects\CreateProjectAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Datasets\ImportDatasetIntoProjectRequest;
use App\Jobs\Datasets\ImportDatasetIntoProjectJob;
use App\Models\Dataset;
use App\Models\Project;
use App\Models\User;

class ImportDatasetIntoProjectWebController extends Controller
{
    use ImportDataset;

    public function __invoke(ImportDatasetIntoProjectRequest $request, Dataset $dataset)
    {
        $validated = $request->validated();
        $project = $this->getOrCreateProject($validated);
        abort_unless($this->allowedToImportDataset($dataset, $project), 403);
        ImportDatasetIntoProjectJob::dispatch($dataset, $project, $validated['directory_name'])->onQueue('globus');
        return redirect(route('projects.show', [$project]));
    }

    private function getOrCreateProject(array $validated)
    {
        if (isset($validated['project_id'])) {
            return Project::findOrFail($validated['project_id']);
        }

        $createProjectAction = new CreateProjectAction();
        $rv = $createProjectAction->execute(['name' => $validated['project_name']], auth()->id());
        return $rv['project'];
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
