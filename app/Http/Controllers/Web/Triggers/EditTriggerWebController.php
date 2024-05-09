<?php

namespace App\Http\Controllers\Web\Triggers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Script;
use App\Models\ScriptTrigger;
use Illuminate\Http\Request;

class EditTriggerWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project, ScriptTrigger $trigger)
    {
        $trigger->load('script.scriptFile.directory', 'owner');
        $scripts = Script::listForProject($project);
        return view('app.projects.triggers.edit', compact('project', 'trigger', 'scripts'));
    }
}
