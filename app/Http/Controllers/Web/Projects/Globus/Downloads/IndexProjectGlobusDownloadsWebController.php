<?php

namespace App\Http\Controllers\Web\Projects\Globus\Downloads;

use App\Http\Controllers\Controller;
use App\Models\GlobusUploadDownload;
use App\Models\Project;

class IndexProjectGlobusDownloadsWebController extends Controller
{
    public function __invoke(Project $project)
    {
        $user = auth()->user();
        $globusDownloads = GlobusUploadDownload::with('owner')
                                               ->where('owner_id', $user->id)
                                               ->where('project_id', $project->id)
                                               ->where('type', 'download')
                                               ->get();

        return view('app.projects.globus.downloads.index', compact('project', 'globusDownloads', 'user'));
    }
}
