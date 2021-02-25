<?php

namespace App\Http\Controllers\Web\Projects\Globus;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\TransferRequest;
use Illuminate\Http\Request;

class ShowStartedGlobusTransferWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $globusRequestId = $request->route("globusRequest");
        $globusRequest = TransferRequest::find($globusRequestId);
        return view('app.projects.globus.show', compact('globusRequest', 'project'));
    }
}
