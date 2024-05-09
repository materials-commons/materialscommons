<?php

namespace App\Http\Controllers\Api\Files\Scripts;

use App\Actions\Run\RunScriptAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ScriptRuns\ScriptRunResource;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;

class RunScriptApiController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project, File $file)
    {
        abort_unless($file->isRunnable(), 400, "File {$file->name} is not a runnable type.");
        $action = new RunScriptAction();
        $run = $action->execute($file, $project, auth()->user());
        return new ScriptRunResource($run);
    }
}
