<?php

namespace App\Http\Controllers\Web\DataHQ\SamplesHQ;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class CreateDataChartWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Project $project)
    {
        $validatedData = $request->validate([
            'tab' => 'required|string',
            'chart_type' => 'required|string', // Turn into a enum
            'chart_name' => 'required|string',
            'xattr' => 'required|string',
            'yattr' => 'nullable|string'
        ]);

        $stateService = app('sampleshq');
        $state = $stateService->getOrCreateStateForProject($project);
        $tabState = $state->getTabStateByKey($validatedData['tab']);

    }
}
