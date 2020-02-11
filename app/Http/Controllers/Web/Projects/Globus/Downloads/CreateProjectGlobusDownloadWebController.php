<?php

namespace App\Http\Controllers\Web\Projects\Globus\Downloads;

use App\Http\Controllers\Controller;
use App\Models\Project;

class CreateProjectGlobusDownloadWebController extends Controller
{
    public function __invoke(Project $project)
    {
        $user = auth()->user();

        if (!isset($user->globus_user)) {
            return redirect(route('projects.globus.downloads.edit_account', [$project]));
        }

        return view('app.projects.globus.downloads.create', compact('project', 'user'));
    }
}
