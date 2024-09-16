<?php

namespace App\Http\Controllers\Web\DataHQ\ComputationsHQ;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use function view;

class IndexComputationsHQWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        return view('app.projects.datahq.computationshq.index', [
            'project' => $project,
        ]);
    }
}
