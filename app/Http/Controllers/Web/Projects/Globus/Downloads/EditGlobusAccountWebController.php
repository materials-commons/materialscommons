<?php

namespace App\Http\Controllers\Web\Projects\Globus\Downloads;

use App\Http\Controllers\Controller;
use App\Models\Project;

class EditGlobusAccountWebController extends Controller
{
    public function __invoke(Project $project)
    {
        $user = auth()->user();
        return view('app.projects.globus.downloads.globus_account', compact('project', 'user'));
    }
}
