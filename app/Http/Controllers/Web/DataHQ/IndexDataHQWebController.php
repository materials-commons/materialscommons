<?php

namespace App\Http\Controllers\Web\DataHQ;

use App\Http\Controllers\Controller;
use App\Models\DatahqInstance;
use App\Models\Project;
use Illuminate\Http\Request;

class IndexDataHQWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $context = $request->input('context', 'project');
        $explorer = $request->input('explorer', 'overview');
        $view = $request->input('view', 'samples');
        $subview = $request->input('subview', '');
        $instance = DatahqInstance::getOrCreateInstanceForProject($project, auth()->user());
        return view('app.projects.datahq.index', [
            'project'  => $project,
            'context'  => $context,
            'explorer' => $explorer,
            'view'     => $view,
            'subview'  => $subview,
            'instance' => $instance,
        ]);
    }
}
