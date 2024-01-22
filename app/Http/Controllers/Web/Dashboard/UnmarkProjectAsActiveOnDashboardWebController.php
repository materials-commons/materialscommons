<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use function auth;
use function redirect;
use function route;

class UnmarkProjectAsActiveOnDashboardWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $user = auth()->user();
        $user->removeFromActiveProjects($project);
        return redirect(route('dashboard.projects.show'));
    }
}
