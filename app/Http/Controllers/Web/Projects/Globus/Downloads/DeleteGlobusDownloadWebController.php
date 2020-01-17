<?php

namespace App\Http\Controllers\Web\Projects\Globus\Downloads;

use App\Http\Controllers\Controller;
use App\Models\GlobusUploadDownload;
use App\Models\Project;

class DeleteGlobusDownloadWebController extends Controller
{
    public function __invoke(Project $project, GlobusUploadDownload $globusDownload)
    {
        return view('app.projects.globus.downloads.delete', compact('project', 'globusDownload'));
    }
}
