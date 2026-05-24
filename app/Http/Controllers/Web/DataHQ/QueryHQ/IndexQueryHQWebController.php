<?php

namespace App\Http\Controllers\Web\DataHQ\QueryHQ;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class IndexQueryHQWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project)
    {
        return view('app.projects.datahq.queryhq.index', [
            'project' => $project,
        ]);
    }
}
