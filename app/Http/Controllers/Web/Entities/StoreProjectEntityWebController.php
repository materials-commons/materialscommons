<?php

namespace App\Http\Controllers\Web\Entities;

use App\Actions\Entities\CreateEntityAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Entities\CreateEntityRequest;
use App\Models\Project;

class StoreProjectEntityWebController extends Controller
{
    public function __invoke(CreateEntityRequest $request, CreateEntityAction $createEntityAction, Project $project)
    {
        $validated = $request->validated();
        $validated['project_id'] = $project->id;
        $createEntityAction($validated, auth()->id());
        return redirect(route('projects.entities.index', [$project]));
    }
}
