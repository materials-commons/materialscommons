<?php

namespace App\Http\Controllers\Web\Globus\Drop;

use App\Http\Controllers\Controller;
use App\Models\GlobusDrop;
use App\Models\Project;
use Illuminate\Http\Request;

class DeleteGlobusDropWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project, GlobusDrop $drop)
    {
        if ($drop->project_id !== $project->id) {
            flash()->error("You do not have permission to delete this drop.");
            return redirect()->route('projects.globus.drops.index', [$project]);
        }
        $drop->delete();
        flash()->success("Drop deleted successfully.");
        return redirect()->route('projects.globus.drops.index', [$project]);
    }
}
