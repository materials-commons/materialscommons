<?php

namespace App\Http\Controllers\Web\Projects\Globus;

use App\Http\Controllers\Controller;
use App\Models\GlobusUpload;
use App\Models\Project;

class ShowProjectGlobusUploadsStatusWebController extends Controller
{
    public function __invoke(Project $project)
    {
        $globusUploads = GlobusUpload::where('project_id', $project->id)->get();
        return view('app.projects.globus.index', compact('project', 'globusUploads'));
    }
}
