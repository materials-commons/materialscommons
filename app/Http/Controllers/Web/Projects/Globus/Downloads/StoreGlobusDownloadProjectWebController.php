<?php

namespace App\Http\Controllers\Web\Projects\Globus\Downloads;

use App\Actions\Globus\Downloads\CreateGlobusDownloadForProjectAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Globus\CreateGlobusProjectUploadDownloadRequest;
use App\Jobs\Globus\CreateGlobusProjectDownloadDirsJob;
use App\Models\Project;

class StoreGlobusDownloadProjectWebController extends Controller
{
    public function __invoke(CreateGlobusProjectUploadDownloadRequest $request, Project $project)
    {
        $validated = $request->validated();
        $createGlobusDownloadForProjectAction = new CreateGlobusDownloadForProjectAction();
        $globusDownload = $createGlobusDownloadForProjectAction($validated, $project->id, auth()->user());
        $createGlobusProjectDownloadDirsJob = new CreateGlobusProjectDownloadDirsJob($globusDownload, auth()->user());
        dispatch($createGlobusProjectDownloadDirsJob)->onQueue('globus');
        return redirect(route('projects.globus.downloads.show', [$project, $globusDownload]));
    }
}
