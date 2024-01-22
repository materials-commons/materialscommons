<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use function flash;
use function now;
use function redirect;
use function route;

class UnarchiveProjectOnDashboardWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $project->update(['archived_at' => null]);
        flash("Project {$project->name} successfully unarchived!")->success();
        return redirect(route('dashboard.projects.archived.index'));
    }
}
