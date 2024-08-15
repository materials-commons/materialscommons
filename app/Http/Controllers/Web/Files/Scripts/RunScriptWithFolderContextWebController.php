<?php

namespace App\Http\Controllers\Web\Files\Scripts;

use App\Actions\Run\RunScriptAction;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;
use function auth;
use function flash;
use function redirect;
use function route;

class RunScriptWithFolderContextWebController extends Controller
{
    public function __invoke(Request $request, Project $project, File $dir, File $file)
    {
        if (!$file->isRunnable()) {
            flash("Selected file {$file->name} is not a runnable file type.")->error();
            return redirect(route('projects.folders.show', [$project, $dir]));
        }

        $action = new RunScriptAction();
        $run = $action->execute($file, $project, auth()->user(), $dir);
        flash("Queued up {$file->name} to run")->info();
        return redirect(route('projects.runs.show', [$project, $run]));
    }
}
