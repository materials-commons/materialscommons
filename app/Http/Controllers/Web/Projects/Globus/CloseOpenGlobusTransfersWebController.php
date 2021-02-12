<?php

namespace App\Http\Controllers\Web\Projects\Globus;

use App\Http\Controllers\Controller;
use App\Models\GlobusRequest;
use App\Models\Project;

class CloseOpenGlobusTransfersWebController extends Controller
{
    public function __invoke(Project $project)
    {
        $user = auth()->user();

        GlobusRequest::where('project_id', $project->id)
                     ->where('owner_id', $user->id)
                     ->where('state', 'new')
                     ->update(['state' => 'closed']);
        flash("Marked globus transfers as finished.")->success();
        return redirect()->back();
    }
}
