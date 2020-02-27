<?php

namespace App\Http\Controllers\Web\Projects\Globus\Uploads;

use App\Http\Controllers\Controller;
use App\Models\Project;

class CreateProjectGlobusUploadWebController extends Controller
{
    public function __invoke(Project $project)
    {
        $user = auth()->user();

        if (!isset($user->globus_user)) {
            return redirect(route('projects.globus.uploads.edit_account', [$project]));
        }

        return view('app.projects.globus.uploads.create', compact('project', 'user'));
    }
}
