<?php

namespace App\Http\Controllers\Web\Projects\Globus;

use App\Http\Controllers\Controller;
use App\Models\GlobusUploadDownload;
use App\Models\Project;

class ShowProjectGlobusUploadsStatusWebController extends Controller
{
    public function __invoke(Project $project)
    {
        $globusUploads = GlobusUploadDownload::where('project_id', $project->id)
                                             ->where('type', 'upload')
                                             ->get();
        $user = auth()->user();

        return view('app.projects.globus.index', compact('project', 'globusUploads', 'user'));
    }
}
