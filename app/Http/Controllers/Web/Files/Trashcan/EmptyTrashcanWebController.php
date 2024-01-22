<?php

namespace App\Http\Controllers\Web\Files\Trashcan;

use App\Http\Controllers\Controller;
use App\Jobs\Trashcan\EmptyTrashcanJob;
use App\Models\Project;
use Illuminate\Http\Request;

class EmptyTrashcanWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        EmptyTrashcanJob::dispatch($project);
        flash("Trashcan will be emptied in the background. Some values may show up until the background job executes.")->success();
        return redirect(route('projects.trashcan.index', ['project' => $project]));
    }
}
