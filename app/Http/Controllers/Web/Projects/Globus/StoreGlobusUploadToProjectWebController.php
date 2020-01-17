<?php

namespace App\Http\Controllers\Web\Projects\Globus;

use App\Actions\Globus\FinishCreatingGlobusUploadAction;
use App\Actions\Globus\GlobusApi;
use App\Http\Controllers\Controller;
use App\Http\Requests\Globus\CreateGlobusProjectUploadRequest;
use App\Models\GlobusUploadDownload;
use App\Models\Project;

class StoreGlobusUploadToProjectWebController extends Controller
{
    public function __invoke(CreateGlobusProjectUploadRequest $request, Project $project)
    {
        $validated = $request->validated();
        $validated['project_id'] = $project->id;
        $validated['owner_id'] = auth()->id();
        $validated['loading'] = false;
        $validated['uploading'] = true;
        $validated['type'] = 'upload';

        $globusUpload = GlobusUploadDownload::create($validated);
        $globusApi = GlobusApi::createGlobusApi();

        $finishCreatingGlobusUploadAction = new FinishCreatingGlobusUploadAction($globusApi);
        $globusUpload = $finishCreatingGlobusUploadAction($globusUpload, auth()->user());
        return redirect(route('projects.globus.uploads.show', [$project, $globusUpload]));
    }
}
