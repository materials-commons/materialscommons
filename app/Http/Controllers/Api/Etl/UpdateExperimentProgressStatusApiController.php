<?php

namespace App\Http\Controllers\Api\Etl;

use App\Actions\Etl\UpdateExperimentProgressAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UpdateExperimentProgressStatusApiController extends Controller
{
    public function __invoke(Request $request, UpdateExperimentProgressAction $updateExperimentProgressAction)
    {
        $validated = $request->validate([
            'project_id'    => 'required|integer',
            'experiment_id' => 'required|integer',
            'loading'       => 'required|boolean',
        ]);

        $updateExperimentProgressAction($validated['experiment_id'], $validated['loading']);
        return response()->json(['success' => true]);
    }
}
