<?php

namespace App\Http\Controllers\Web\Projects\Users;

use App\Http\Controllers\Controller;
use App\Models\Project;

class IndexProjectUsersWebController extends Controller
{
    public function __invoke($projectId)
    {
        $project = Project::with('users')->findOrFail($projectId);
        return view('app.projects.users.index', ['project' => $project]);
    }
}
