<?php

namespace App\Http\Controllers\Api\Experiments;

use App\Actions\Experiments\ReloadExperimentAction;
use App\Actions\GoogleSheets\CreateGoogleSheetAction;
use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Traits\GoogleSheets;
use Illuminate\Http\Request;

class ReloadExperimentFromGoogleSheetApiController extends Controller
{
    use GoogleSheets;

    public function __invoke(Request $request)
    {
        $request->validate([
            'project_id'      => 'required|integer',
            'experiment_id'   => 'required|integer',
            'google_sheet_id' => 'required|string',
        ]);

        $user = auth()->user();

        $experimentId = $request->input('experiment_id'); // Token is the UUID for the experiment
        $googleSheetId = $request->input('google_sheet_id');

        $sheetUrl = $this->getGoogleSheetsUrlFromId($googleSheetId);
        $experiment = Experiment::with(['project'])
                                ->where('id', $experimentId)
                                ->first();
        if (is_null($experiment)) {
            return response()->json([
                'error'   => 'Not Found',
                'message' => "Unknown experiment id {$experimentId}"
            ], 404);
        }

        if ($experiment->project->id != $request->input('project_id')) {
            return response()->json([
                'error'   => 'Forbidden',
                'message' => "Experiment project id {$experiment->project->id} does not match request project id {$request->input('project_id')}"
            ], 403);
        }

        $createGoogleSheetAction = new CreateGoogleSheetAction();
        ray($sheetUrl);
        $sheet = $createGoogleSheetAction->execute($sheetUrl, $experiment->project, $user);
        $experiment->update(['sheet_id' => $sheet->id, 'loaded_file_path' => null]);
        $reloadExperimentAction = new ReloadExperimentAction();
        $reloadExperimentAction->execute($experiment->project, $experiment, null, $sheetUrl, $user->id);

        return response()->json(['status' => 'Queued', 'message' => 'Loading experiment from google sheet']);
    }
}
