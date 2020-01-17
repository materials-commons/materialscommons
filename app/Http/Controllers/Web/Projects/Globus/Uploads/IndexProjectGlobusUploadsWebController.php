<?php

namespace App\Http\Controllers\Web\Projects\Globus\Uploads;

use App\Http\Controllers\Controller;
use App\Models\GlobusUploadDownload;
use App\Models\Project;

class IndexProjectGlobusUploadsWebController extends Controller
{
    public function __invoke(Project $project)
    {
        $user = auth()->user();
        $globusUploads = GlobusUploadDownload::with('owner')
                                             ->where('project_id', $project->id)
                                             ->where('type', 'upload')
                                             ->get();

        return view('app.projects.globus.uploads.index', compact('project', 'globusUploads', 'user'));
    }
}
