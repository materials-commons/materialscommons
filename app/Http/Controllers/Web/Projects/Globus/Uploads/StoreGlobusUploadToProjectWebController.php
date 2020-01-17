<?php

namespace App\Http\Controllers\Web\Projects\Globus\Uploads;

use App\Actions\Globus\GlobusApi;
use App\Actions\Globus\Uploads\CreateGlobusUploadAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Globus\CreateGlobusProjectUploadDownloadRequest;
use App\Models\Project;

class StoreGlobusUploadToProjectWebController extends Controller
{
    public function __invoke(CreateGlobusProjectUploadDownloadRequest $request, Project $project)
    {
        $validated = $request->validated();
        $createGlobusUploadAction = new CreateGlobusUploadAction(GlobusApi::createGlobusApi());
        $globusUpload = $createGlobusUploadAction($validated, $project->id, auth()->user());
        return redirect(route('projects.globus.uploads.show', [$project, $globusUpload]));
    }
}
