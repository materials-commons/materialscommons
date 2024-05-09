<?php

namespace App\Http\Controllers\Web\Projects\Runs;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ScriptRun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShowScriptRunStatusWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project, ScriptRun $run)
    {
        $log = $run->getLogContents();
        $run->load('script.scriptFile.directory');
        return view('app.projects.runs.show', compact('project', 'run', 'log'));
    }
}
