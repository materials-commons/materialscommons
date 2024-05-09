<?php

namespace App\Http\Controllers\Web\Projects\Runs;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ScriptRun;
use Illuminate\Http\Request;

class IndexScriptRunsWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project)
    {
        $scriptRuns = ScriptRun::with(['script.scriptFile.directory', 'owner'])
                               ->where('project_id', $project->id)
                               ->get();
        return view('app.projects.runs.index', compact('project', 'scriptRuns'));
    }
}
