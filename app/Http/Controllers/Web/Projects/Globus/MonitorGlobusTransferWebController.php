<?php

namespace App\Http\Controllers\Web\Projects\Globus;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\TransferRequest;

class MonitorGlobusTransferWebController extends Controller
{
    public function __invoke(Project $project, TransferRequest $globusRequest)
    {
        $globusRequest = TransferRequest::where('project_id', $project->id)
                                        ->where('owner_id', auth()->id())
                                        ->first();
        return view('app.projects.globus.monitor', compact('project', 'globusRequest'));
    }
}
