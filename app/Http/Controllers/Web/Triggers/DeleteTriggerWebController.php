<?php

namespace App\Http\Controllers\Web\Triggers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ScriptTrigger;
use Illuminate\Http\Request;
use function view;

class DeleteTriggerWebController extends Controller
{
    public function __invoke(Request $request, Project $project, ScriptTrigger $trigger)
    {
        $trigger->load(['script.scriptFile.directory', 'owner']);
        return view('app.projects.triggers.delete', [
            'project' => $project,
            'trigger' => $trigger,
        ]);
    }
}
