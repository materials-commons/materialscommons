<?php

namespace App\Http\Controllers\Api\Experiments;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use Illuminate\Http\Request;

class ReloadExperimentFromGoogleSheetUnauthenticatedApiController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'experimentUUID' => 'required|string',
            'googleSheetId' => 'required|string',
        ]);

        $experimentUUID = $request->input('experimentUUID');
        $googleSheetID = $request->input('googleSheetId');

        $experiment = Experiment::where('uuid', $experimentUUID)->first();


    }
}
