<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Actions\Experiments\ReloadExperimentAction;
use App\Actions\GoogleSheets\CreateGoogleSheetAction;
use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;
use App\Models\Sheet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use function auth;
use function flash;
use function is_null;
use function redirect;
use function route;
use const PHP_URL_HOST;

class ReloadExperimentWebController extends Controller
{
    public function __invoke(Request    $request, ReloadExperimentAction $reloadExperimentAction, Project $project,
                             Experiment $experiment)
    {
        $validated = $request->validate([
            'file_id'   => 'nullable|integer',
            'sheet_url' => 'nullable|url',
            'sheet_id'  => 'nullable|integer'
        ]);

        ray($validated);

        $fileId = $request->get('file_id');
        $sheetUrl = $request->get('sheet_url');
        $sheetId = $request->get('sheet_id');

        if (!$this->onlySheetIdOrSheetUrlOrFileIdSpecified($validated)) {
            flash("You can only specify a google sheet url, choose a known sheet, or select an excel file. You selected multiple or none of these choices")->error();
            return redirect(route('projects.experiments.show', [$project, $experiment]));
        }

        $sheet = null;
        if (!is_null($sheetUrl)) {
            $createGoogleSheetAction = new CreateGoogleSheetAction();
            $sheet = $createGoogleSheetAction->execute($sheetUrl, $project, auth()->user());
        } elseif (!is_null($sheetId)) {
            $sheet = Sheet::where("project_id", $project->id)
                          ->where("id", $sheetId)
                          ->first();
        }

        if (!is_null($sheet)) {
            $sheetUrl = $sheet->url;
        }

        ray("reloadExperiment {$fileId}, {$sheetUrl}");
        if ($reloadExperimentAction->execute($project, $experiment, $fileId, $sheetUrl, auth()->id())) {
            flash("Reloading experiment {$experiment->name} in background.")->success();
        } else {
            flash("Failed reloading, no changes made to experiment.")->error();
        }

        return redirect(route('projects.experiments.show', [$project, $experiment]));
    }

    private function onlySheetIdOrSheetUrlOrFileIdSpecified(mixed $validated)
    {
        $countNotNull = 0;
        if (isset($validated["file_id"])) {
            $countNotNull++;
        }

        if (isset($validated["sheet_id"])) {
            $countNotNull++;
        }

        if (isset($validated["sheet_url"])) {
            $countNotNull++;
        }

        return $countNotNull == 1;
    }
}
