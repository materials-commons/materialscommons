<?php

namespace App\Http\Controllers\Web\Projects\Globus;

use App\Http\Controllers\Controller;
use App\Models\GlobusUploadDownload;
use App\Models\Project;

class ShowGlobusUploadToMarkAsCompleteWebController extends Controller
{
    public function __invoke(Project $project, GlobusUploadDownload $globusUpload)
    {
        return view('app.projects.globus.uploads.complete', compact('project', 'globusUpload'));
    }
}
