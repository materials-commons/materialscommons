<?php

namespace App\Http\Controllers\Web\DataHQ\SamplesHQ;

use App\DTO\DataHQ\SubviewState;
use App\DTO\DataHQ\ViewAttr;
use App\DTO\DataHQ\ViewStateData;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\DataHQ\DataHQStateStoreInterface;
use Illuminate\Http\Request;

class CreateDataChartWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project)
    {
        $validatedData = $request->validate([
            'tab'        => 'required|string',
            'chart_type' => 'required|string', // Turn into an enum
            'chart_name' => 'required|string',
            'xattr_type' => 'nullable|string', // Turn into an enum
            'xattr'      => 'nullable|string',
            'yattr'      => 'required|string',
            'yattr_type' => 'required|string', // Turn into an enum
        ]);

        $stateService = app('sampleshq');
        $state = $stateService->getOrCreateStateForProject($project);
        $tabState = $state->getTabStateByKey($validatedData['tab']);
        $subviewState = new SubviewState($validatedData['chart_name'], SubviewState::makeKey(), 'chart');
        $yattr = new ViewAttr($validatedData['yattr_type'], $validatedData['yattr']);
        $xattr = null;
        if (isset($validatedData['xattr'])) {
            $xattr = new ViewAttr($validatedData['xattr_type'], $validatedData['xattr']);
        }

        $viewStateData = ViewStateData::makeChartViewStateData($validatedData['chart_type'], $xattr, $yattr);
        $subviewState->viewData = $viewStateData;
        $tabState->subviews->push($subviewState);
        $stateService->saveStateForProject($project, $state);
        return $subviewState->key;
    }
}
