<?php

namespace App\Http\Controllers\Web\PublishWizard;

use App\Actions\Projects\CreateProjectAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\CreateProjectRequest;

class StoreProjectStepWebController extends Controller
{
    public function __invoke(CreateProjectRequest $request, CreateProjectAction $createProjectAction)
    {
        $validated = $request->validated();
        $rv = $createProjectAction->execute($validated, auth()->id());
        $project = $rv['project'];
        return redirect(route('projects.datasets.create', [$project]));
    }
}
