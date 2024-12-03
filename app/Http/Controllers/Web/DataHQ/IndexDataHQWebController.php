<?php

namespace App\Http\Controllers\Web\DataHQ;

use App\Http\Controllers\Controller;
use App\Models\DatahqInstance;
use App\Models\Project;
use Illuminate\Http\Request;
use function auth;

class IndexDataHQWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $selectedView = $request->input('view', 'overview');
        $tab = $request->input('tab', 'samples');
        $instance = DatahqInstance::getOrCreateActiveDatahqInstanceForUser(auth()->user(), $project);
        return view('app.projects.datahq.index', [
            'project'  => $project,
            'instance' => $instance,
            'view'     => $instance->currentDatahqView(),
            'tab'      => $request->input('tab', 'samples')
        ]);
    }
}
