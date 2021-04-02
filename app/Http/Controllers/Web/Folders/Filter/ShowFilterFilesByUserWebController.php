<?php

namespace App\Http\Controllers\Web\Folders\Filter;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ShowFilterFilesByUserWebController extends Controller
{
    public function __invoke(Project $project)
    {
        $project->load(['team.members', 'team.admins']);
        $members = collect();
        foreach ($project->team->admins as $admin) {
            $members->push($admin);
        }
        foreach ($project->team->members as $member) {
            $members->push($member);
        }
        return view('app.projects.folders.filters.by-user', compact('project', 'members'));
    }
}
