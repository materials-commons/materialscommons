<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use function config;
use function flash;

class ImmediatelyDestroyProjectWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        // Delete by setting the deleted_at past the date where they get deleted.
        $old = Carbon::now()->subDays(config('trash.expires_in_days') + 1);
        $project->update(['deleted_at' => $old]);
        flash("Project {$project->name} is scheduled to be deleted in the background starting immediately.")->success();
        return redirect(route('dashboard.projects.trash.index'));
    }
}
