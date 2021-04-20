<?php

namespace App\Http\Controllers\Web\Projects\Globus\Uploads;

use App\Http\Controllers\Controller;
use App\Jobs\Globus\DeleteGlobusUploadDownloadJob;
use App\Models\GlobusUploadDownload;
use App\Models\Project;

class DestroyGlobusUploadWebController extends Controller
{
    public function __invoke(Project $project, GlobusUploadDownload $globusUpload)
    {
        DeleteGlobusUploadDownloadJob::dispatch($globusUpload)->onQueue("globus");
        flash("Globus upload queued for deletion")->info();
        return redirect(route('projects.globus.uploads.index', [$project]));
    }
}
