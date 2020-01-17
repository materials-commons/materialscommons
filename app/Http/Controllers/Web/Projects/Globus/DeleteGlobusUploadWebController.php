<?php

namespace App\Http\Controllers\Web\Projects\Globus;

use App\Http\Controllers\Controller;
use App\Models\GlobusUpload;
use App\Models\Project;

class DeleteGlobusUploadWebController extends Controller
{
    public function __invoke(Project $project, GlobusUpload $globusUpload)
    {
        return view('app.projects.globus.uploads.delete', compact('project', 'globusUpload'));
    }
}
