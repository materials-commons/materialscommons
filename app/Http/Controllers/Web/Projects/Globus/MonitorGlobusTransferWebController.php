<?php

namespace App\Http\Controllers\Web\Projects\Globus;

use App\Http\Controllers\Controller;
use App\Models\GlobusRequest;
use App\Models\Project;

class MonitorGlobusTransferWebController extends Controller
{
    public function __invoke(Project $project, GlobusRequest $globusRequest)
    {
        $globusRequest = GlobusRequest::where('project_id', $project->id)
                                      ->where('owner_id', auth()->id())
                                      ->first();
        return view('app.projects.globus.monitor', compact('project', 'globusRequest'));
    }
}
