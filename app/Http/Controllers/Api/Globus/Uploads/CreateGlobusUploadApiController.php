<?php

namespace App\Http\Controllers\Api\Globus\Uploads;

use App\Actions\Globus\GlobusApi;
use App\Actions\Globus\Uploads\CreateGlobusUploadAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Globus\CreateGlobusProjectUploadDownloadRequest;
use Illuminate\Support\Arr;

class CreateGlobusUploadApiController extends Controller
{

    public function __invoke(CreateGlobusProjectUploadDownloadRequest $request)
    {
        $validated = $request->validated();
        $projectId = $validated['project_id'];
        $createGlobusUploadAction = new CreateGlobusUploadAction(GlobusApi::createGlobusApi());
        return $createGlobusUploadAction(Arr::except($validated, ['project_id']), $projectId, auth()->user());
    }
}
