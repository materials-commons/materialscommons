<?php

namespace App\Http\Controllers\Web\Projects;

use App\Actions\Projects\CreateProjectAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\CreateProjectRequest;

class StoreProjectWebController extends Controller
{
    public function __invoke(CreateProjectRequest $request, CreateProjectAction $createProjectAction)
    {
        $validated = $request->validated();
        $createProjectAction($validated);
        return redirect(route('projects.index'));
    }

}
