<?php

namespace App\Http\Controllers\Api\Globus\Downloads;

use App\Actions\Globus\Downloads\CreateGlobusDownloadForProjectAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Globus\CreateGlobusProjectUploadDownloadRequest;
use App\Http\Resources\Globus\GlobusUploadDownloadResource;
use App\Jobs\Globus\CreateGlobusProjectDownloadDirsJob;
use Illuminate\Support\Arr;

class CreateGlobusDownloadApiController extends Controller
{
    public function __invoke(CreateGlobusProjectUploadDownloadRequest $request)
    {
        $validated = $request->validated();
        $projectId = $validated['project_id'];
        $createGlobusDownloadForProjectAction = new CreateGlobusDownloadForProjectAction();
        $globusDownload = $createGlobusDownloadForProjectAction(Arr::except($validated, ['project_id']), $projectId,
            auth()->user());
        $createGlobusProjectDownloadDirsJob = new CreateGlobusProjectDownloadDirsJob($globusDownload, auth()->user());
        dispatch($createGlobusProjectDownloadDirsJob)->onQueue('globus');
        return new GlobusUploadDownloadResource($globusDownload);
    }
}
