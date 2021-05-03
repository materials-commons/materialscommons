<?php

namespace App\Http\Controllers\Web\Projects\Globus\Downloads;

use App\Http\Controllers\Controller;
use App\Jobs\Globus\DeleteGlobusUploadDownloadJob;
use App\Models\GlobusUploadDownload;
use App\Models\Project;

class DestroyGlobusDownloadWebController extends Controller
{
    public function __invoke(Project $project, GlobusUploadDownload $globusDownload)
    {
        DeleteGlobusUploadDownloadJob::dispatch($globusDownload)->onQueue("globus");
        flash("Globus download queued for deletion")->info();
        return redirect(route('projects.globus.downloads.index', [$project]));
    }
}
