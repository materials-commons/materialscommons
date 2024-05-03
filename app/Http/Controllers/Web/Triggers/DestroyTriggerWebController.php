<?php

namespace App\Http\Controllers\Web\Triggers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ScriptTrigger;
use Illuminate\Http\Request;

class DestroyTriggerWebController extends Controller
{
    public function __invoke(Request $request, Project $project, ScriptTrigger $trigger)
    {
        $trigger->delete();
        return redirect(route('projects.triggers.index', [$project]));
    }
}
