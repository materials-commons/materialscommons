<?php

namespace App\Http\Controllers\Web\DataHQ;

use App\DTO\DataHQ\SubviewState;
use App\DTO\DataHQ\TabState;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class AddFilteredViewWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $stateService = app($request->input('state-service'));
        $state = $stateService->getOrCreateStateForProject($project);

        // 1 tab will the default tab, all other tabs will be filtered views. So subtract one from
        // the count to get the next filtered tab name
        $count = $state->tabs->count();

        $name = "Filtered View {$count}";
        $key = "fv{$count}";
        $ts = new TabState($name, $key);
        $subviewState = new SubviewState('All Samples', 'all-samples', 'samples');
        $ts->subviews->push($subviewState);
        $state->tabs->push($ts);
        $stateService->saveStateForProject($project, $state);
        return redirect()->route('projects.datahq.sampleshq.index', [$project, 'tab' => $key]);
    }
}
