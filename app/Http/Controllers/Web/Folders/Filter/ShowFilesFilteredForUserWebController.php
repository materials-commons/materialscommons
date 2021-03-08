<?php

namespace App\Http\Controllers\Web\Folders\Filter;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;

class ShowFilesFilteredForUserWebController extends Controller
{
    public function __invoke(Project $project, User $user)
    {
        return view('app.projects.folders.filters.show-for-user', compact('project', 'user'));
    }
}
