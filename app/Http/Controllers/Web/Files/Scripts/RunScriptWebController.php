<?php

namespace App\Http\Controllers\Web\Files\Scripts;

use App\Actions\Run\RunScriptAction;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;
use function redirect;
use function route;

class RunScriptWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project, File $file)
    {
        if (!$file->isRunnable()) {
            flash("Selected file {$file->name} is not a runnable file type.")->error();
            return redirect(route('projects.files.show', [$project, $file]));
        }

        $action = new RunScriptAction();
        $action->execute($file, $project, auth()->user());
        flash("Queued up {$file->name} to run")->info();
        return redirect(route('projects.files.show', [$project, $file]));
    }
}
