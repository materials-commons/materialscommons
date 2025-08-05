<?php

namespace App\Http\Controllers\Api\Experiments;

use App\Actions\Experiments\ReloadExperimentAction;
use App\Actions\GoogleSheets\CreateGoogleSheetAction;
use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Traits\GoogleSheets;
use Illuminate\Http\Request;

class ReloadExperimentFromGoogleSheetUnauthenticatedApiController extends Controller
{
    use GoogleSheets;

    public function __invoke(Request $request)
    {
        $request->validate([
            'experiment_token' => 'required|string',
            'google_sheet_id'  => 'required|string',
        ]);

        $experimentToken = $request->input('experiment_token'); // Token is the UUID for the experiment
        $googleSheetID = $request->input('google_sheet_id');

        $sheetUrl = $this->getGoogleSheetsUrlFromId($googleSheetID);
        $experiment = Experiment::with(['project', 'owner'])
                                ->where('uuid', $experimentToken)
                                ->first();
        if (is_null($experiment)) {
            return response()->json(['error' => 'Not Found', 'message' => "Unknown experiment token {$experimentToken}"], 404);
        }

        $createGoogleSheetAction = new CreateGoogleSheetAction();
        ray($sheetUrl);
        $sheet = $createGoogleSheetAction->execute($sheetUrl, $experiment->project, $experiment->owner);
        $experiment->update(['sheet_id' => $sheet->id, 'loaded_file_path' => null]);
        $reloadExperimentAction = new ReloadExperimentAction();
        $reloadExperimentAction->execute($experiment->project, $experiment, null, $sheetUrl, $experiment->owner->id);

        return response()->json(['status' => 'Queued', 'message' => 'Loading experiment from google sheet']);
    }
}
