<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RestoreProjectFromTrashWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $project->update(['deleted_at' => null]);
        if (Str::startsWith($project->name, "{$project->uuid}-")) {
            $project->update(['name' => Str::replaceStart("{$project->uuid}-", '', $project->name)]);
        }
        return redirect(route('dashboard.projects.trash.index'));
    }
}
