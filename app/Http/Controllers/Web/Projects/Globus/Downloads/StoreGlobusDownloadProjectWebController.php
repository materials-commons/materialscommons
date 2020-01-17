<?php

namespace App\Http\Controllers\Web\Projects\Globus\Downloads;

use App\Actions\Globus\Downloads\CreateGlobusDownloadForProjectAction;
use App\Actions\Globus\GlobusApi;
use App\Http\Controllers\Controller;
use App\Http\Requests\Globus\CreateGlobusProjectUploadDownloadRequest;
use App\Models\Project;

class StoreGlobusDownloadProjectWebController extends Controller
{
    public function __invoke(CreateGlobusProjectUploadDownloadRequest $request, Project $project)
    {
        $validated = $request->validated();
        $createGlobusDownloadForProjectAction = new CreateGlobusDownloadForProjectAction(GlobusApi::createGlobusApi());
        $globusDownload = $createGlobusDownloadForProjectAction($validated, $project->id, auth()->user());
        return redirect(route('projects.globus.downloads.show', [$project, $globusDownload]));
    }
}
