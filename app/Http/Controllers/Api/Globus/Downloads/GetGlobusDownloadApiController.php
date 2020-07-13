<?php

namespace App\Http\Controllers\Api\Globus\Downloads;

use App\Http\Controllers\Controller;
use App\Http\Resources\Globus\GlobusUploadDownloadResource;
use App\Models\GlobusUploadDownload;

class GetGlobusDownloadApiController extends Controller
{
    public function __invoke($projectId, GlobusUploadDownload $download)
    {
        abort_unless(auth()->id() == $download->owner_id, 400, "No such download request");
        abort_unless($projectId == $download->project_id, 400, "No such download request");
        return new GlobusUploadDownloadResource($download);
    }
}
