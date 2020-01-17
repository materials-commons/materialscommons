<?php

namespace App\Http\Controllers\Web\Projects\Globus\Downloads;

use App\Http\Controllers\Controller;
use App\Models\GlobusUploadDownload;
use App\Models\Project;

class ShowProjectGlobusDownloadWebController extends Controller
{
    public function __invoke(Project $project, GlobusUploadDownload $globusDownload)
    {
        return view('app.projects.globus.downloads.show', compact('project', 'globusDownload'));
    }
}
