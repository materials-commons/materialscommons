<?php

namespace App\Http\Controllers\Web\Globus\Drop;

use App\Http\Controllers\Controller;
use App\Models\GlobusDrop;
use App\Models\Project;
use Illuminate\Http\Request;

class ImportGlobusDropWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project, GlobusDrop $drop)
    {
        abort_unless($drop->project_id === $project->id, 404);

        return view('app.projects.globus.drops.import', [
            'project' => $project,
            'drop'    => $drop,
        ]);
    }
}
