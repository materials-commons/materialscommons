<?php

namespace App\Http\Controllers\Web\Projects\Globus;

use App\Actions\Globus\CreateGlobusUploadAction;
use App\Actions\Globus\GlobusApi;
use App\Http\Controllers\Controller;
use App\Http\Requests\Globus\CreateGlobusProjectUploadRequest;
use App\Models\Project;

class StoreGlobusUploadToProjectWebController extends Controller
{
    public function __invoke(CreateGlobusProjectUploadRequest $request, Project $project)
    {
        $validated = $request->validated();
        $CreateGlobusUploadAction = new CreateGlobusUploadAction(GlobusApi::createGlobusApi());
        $globusUpload = $CreateGlobusUploadAction($validated, $project->id, auth()->user());
        return redirect(route('projects.globus.uploads.show', [$project, $globusUpload]));
    }
}
