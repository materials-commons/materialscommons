<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ArchiveProjectOnDashboardWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $project->update(['archived_at' => now()]);
        return redirect(route('dashboard.projects.show'));
    }
}
