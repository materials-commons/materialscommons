<?php

namespace App\Http\Controllers\Web\Projects\Globus\Uploads;

use App\Http\Controllers\Controller;
use App\Models\Project;

class EditGlobusAccountForUploadsWebController extends Controller
{
    public function __invoke(Project $project)
    {
        $user = auth()->user();
        return view('app.projects.globus.uploads.globus_account', compact('project', 'user'));
    }
}
