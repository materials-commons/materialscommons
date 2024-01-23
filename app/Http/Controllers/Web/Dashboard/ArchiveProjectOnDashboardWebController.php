<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use function auth;

class ArchiveProjectOnDashboardWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $project->update(['archived_at' => now()]);
        $user = auth()->user();
        $user->removeFromActiveProjects($project);
        $user->removeFromRecentlyAccessedProjects($project);
        flash("Project {$project->name} successfully archived!")->success();
        return redirect(route('dashboard.projects.show'));
    }
}
