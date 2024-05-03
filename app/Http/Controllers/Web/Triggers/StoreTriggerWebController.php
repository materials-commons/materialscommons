<?php

namespace App\Http\Controllers\Web\Triggers;

use App\DTOs\TriggerDTO;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Script;
use App\Models\ScriptTrigger;
use Illuminate\Http\Request;

class StoreTriggerWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name'           => 'required|string',
            'description'    => 'required|string',
            'what'           => 'required|string',
            'when'           => 'required|string',
            'script_file_id' => 'required|integer',
        ]);

        $triggerDTO = TriggerDTO::fromArray($validated);

        $script = Script::where('script_file_id', $triggerDTO->scriptFileId)
                        ->first();
        if (is_null($script)) {
            flash("No such script")->error();
            return back();
        }

        ScriptTrigger::create([
            'project_id'  => $project->id,
            'name'        => $triggerDTO->name,
            'description' => $triggerDTO->description,
            'what'        => $triggerDTO->what,
            'when'        => $triggerDTO->when,
            'script_id'   => $script->id,
            'owner_id'    => auth()->user()->id,
        ]);

        return redirect(route('projects.triggers.index', [$project]));
    }
}
