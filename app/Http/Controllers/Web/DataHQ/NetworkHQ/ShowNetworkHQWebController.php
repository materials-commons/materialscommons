<?php

namespace App\Http\Controllers\Web\DataHQ\NetworkHQ;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ShowNetworkHQWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project)
    {
        return view('app.projects.datahq.network', [
            'project' => $project,
        ]);
    }
}
