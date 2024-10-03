<?php

namespace App\Http\Controllers\Web\DataHQ;

use App\DTO\DataHQ\SubviewState;
use App\DTO\DataHQ\TabState;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\DataHQ\DataHQStateStore;
use Illuminate\Http\Request;

class AddFilteredViewWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $stateService = DataHQStateStore::getState()->getContextStateStore();
        $state = $stateService->getOrCreateState();

        // 1st tab will the default (index) tab, all other tabs will be filtered views.
        $count = $state->tabs->count();

        $name = "Filtered View {$count}";
        $key = "fv{$count}";
        $ts = new TabState($name, $key);
        $subviewState = new SubviewState('All Samples', 'index', 'samples');
        $ts->subviews->push($subviewState);
        $state->tabs->push($ts);
        $stateService->saveState($state);
        return redirect()->route('projects.datahq.sampleshq.index',
            [$project, 'tab' => $key, 'subview' => 'index']);
    }
}
