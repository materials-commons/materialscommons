<?php

namespace App\Http\Controllers\Web\DataHQ\SamplesHQ;

use App\DTO\DataHQ\SubviewState;
use App\DTO\DataHQ\ViewAttr;
use App\DTO\DataHQ\ViewStateData;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\DataHQ\DataHQContextStateStoreInterface;
use App\Services\DataHQ\DataHQStateStore;
use Illuminate\Http\Request;

class CreateChartSubviewWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project)
    {
        $validatedData = $request->validate([
            'tab'        => 'required|string',
            'chart_name' => 'required|string',
        ]);

        $stateService = DataHQStateStore::getState()->getContextStateStore();
        $state = $stateService->getOrCreateState();
        $tabState = $state->getTabStateByKey($validatedData['tab']);
        $subviewState = new SubviewState($validatedData['chart_name'], SubviewState::makeKey(), 'chart');
        $tabState->subviews->push($subviewState);
        $stateService->saveState($state);
        return $subviewState->key;
    }
}
