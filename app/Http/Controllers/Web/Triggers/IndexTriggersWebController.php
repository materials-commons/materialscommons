<?php

namespace App\Http\Controllers\Web\Triggers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ScriptTrigger;
use Illuminate\Http\Request;

class IndexTriggersWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $triggers = ScriptTrigger::getProjectTriggers($project);
        return view('app.projects.triggers.index', compact('project', 'triggers'));
    }
}
