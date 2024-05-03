<?php

namespace App\Http\Controllers\Web\Triggers;

use App\DTOs\TriggerDTO;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Script;
use App\Models\ScriptTrigger;
use Illuminate\Http\Request;
use function back;
use function flash;
use function is_null;

class UpdateTriggerWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project, ScriptTrigger $trigger)
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

        $trigger->update([
            'name'           => $triggerDTO->name,
            'description'    => $triggerDTO->description,
            'what'           => $triggerDTO->what,
            'when'           => $triggerDTO->when,
            'script_file_id' => $triggerDTO->scriptFileId,
        ]);

        return redirect(route('projects.trigger.show', [$project, $trigger]));
    }
}
