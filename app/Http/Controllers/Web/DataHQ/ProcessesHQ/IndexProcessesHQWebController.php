<?php

namespace App\Http\Controllers\Web\DataHQ\ProcessesHQ;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use function view;

class IndexProcessesHQWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        return view('app.projects.datahq.processeshq.index', [
            'project'  => $project,
        ]);
    }
}
