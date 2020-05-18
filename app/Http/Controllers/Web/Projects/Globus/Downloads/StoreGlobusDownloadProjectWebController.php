<?php

namespace App\Http\Controllers\Web\Projects\Globus\Downloads;

use App\Actions\Globus\Downloads\CreateGlobusDownloadForProjectAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Globus\CreateGlobusProjectUploadDownloadRequest;
use App\Jobs\Globus\CreateGlobusProjectDownloadDirsJob;
use App\Models\Project;

class StoreGlobusDownloadProjectWebController extends Controller
{
    public function __invoke(CreateGlobusProjectUploadDownloadRequest $request,
        CreateGlobusDownloadForProjectAction $createGlobusDownloadForProjectAction, Project $project)
    {
        $validated = $request->validated();
        $globusDownload = $createGlobusDownloadForProjectAction($validated, $project->id, auth()->user());
        CreateGlobusProjectDownloadDirsJob::dispatch($globusDownload, auth()->user())->onQueue('globus');
        return redirect(route('projects.globus.downloads.index', [$project]));
    }
}
