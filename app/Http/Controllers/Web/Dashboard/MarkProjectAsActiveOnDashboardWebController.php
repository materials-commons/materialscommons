<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use function auth;
use function redirect;

class MarkProjectAsActiveOnDashboardWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $user = auth()->user();
        $user->addToActiveProjects($project);
        return redirect(route('dashboard.projects.show'));
    }
}
