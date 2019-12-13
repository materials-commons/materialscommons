<?php

namespace App\Http\Controllers\Web\Projects\Users;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;

class ShowProjectUserWebController extends Controller
{
    public function __invoke(Project $project, User $user)
    {
        return view('app.projects.users.show', compact('project', 'user'));
    }
}
