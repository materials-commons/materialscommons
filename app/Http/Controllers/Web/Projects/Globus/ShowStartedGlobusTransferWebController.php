<?php

namespace App\Http\Controllers\Web\Projects\Globus;

use App\Http\Controllers\Controller;
use App\Models\GlobusTransfer;
use App\Models\Project;
use Illuminate\Http\Request;

class ShowStartedGlobusTransferWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $globusTransferId = $request->route("globusRequest");
        $globusTransfer = GlobusTransfer::with(['transferRequest'])->find($globusTransferId);
        return view('app.projects.globus.show', compact('globusTransfer', 'project'));
    }
}
