<?php

namespace App\Http\Controllers\Web\Projects\Globus;

use App\Http\Controllers\Controller;
use App\Models\Project;

class CreateProjectGlobusUploadWebController extends Controller
{
    public function __invoke(Project $project)
    {
        $user = auth()->user();

        return view('app.projects.globus.uploads.create', compact('project', 'user'));
    }
}
