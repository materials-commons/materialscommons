<?php

namespace App\Http\Controllers\Web\DataHQ;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class IndexDataHQWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $context = $request->input('context', 'project');
        $view = $request->input('view', 'overview');
        $tab = $request->input('tab', 'samples');
        $subview = $request->input('subview', '');
        return view('app.projects.datahq.index', [
            'project'  => $project,
            'context' => $context,
            'view'    => $view,
            'tab'     => $tab,
            'subview' => $subview,
        ]);
    }
}
