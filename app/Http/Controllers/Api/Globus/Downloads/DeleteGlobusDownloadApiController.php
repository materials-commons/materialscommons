<?php

namespace App\Http\Controllers\Api\Globus\Downloads;

use App\Http\Controllers\Controller;
use App\Jobs\Globus\DeleteGlobusUploadDownloadJob;
use App\Models\GlobusUploadDownload;

class DeleteGlobusDownloadApiController extends Controller
{

    public function __invoke($projectId, GlobusUploadDownload $globus)
    {
        DeleteGlobusUploadDownloadJob::dispatch($globus)->onQueue("globus");
    }
}
