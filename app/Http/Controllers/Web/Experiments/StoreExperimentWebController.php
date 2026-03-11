<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Actions\Experiments\CreateExperimentAction;
use App\Actions\GoogleSheets\CreateGoogleSheetAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Experiments\CreateExperimentRequest;
use App\Models\Project;
use App\Models\Sheet;
use function redirect;
use function route;

class StoreExperimentWebController extends Controller
{
    public function __invoke(CreateExperimentRequest $request, CreateExperimentAction $createExperimentAction,
                             Project                 $project)
    {

        $validated = $request->validated();
//        if (!$this->onlySheetIdOrSheetUrlOrFileIdSpecified($validated)) {
//            $showOverview = $request->input('show-overview', false);
//            flash("You can only specify a google sheet url, choose a known sheet, or select an excel file. You selected multiple or none of these choices")->error();
//            redirect(route('projects.folders.index', [$project, 'show-overview' => $showOverview]));
//        }

        $sheet = null;
        if (isset($validated['sheet_url'])) {
            $createGoogleSheetAction = new CreateGoogleSheetAction();
            $sheet = $createGoogleSheetAction->execute($validated["sheet_url"], $project, auth()->user());
        } elseif (isset($validated["sheet_id"])) {
            $sheet = Sheet::where("project_id", $project->id)
                          ->where("id", $validated["sheet_id"])
                          ->first();
        }

        if (isset($sheet)) {
            $validated["sheet_url"] = $sheet->url;
        }

        if (isset($validated["sheet_url"]) || isset($validated["file_id"])) {
            $message = " from the Google sheet.";
            if (isset($validated["file_id"])) {
                $message = " from the given excel file.";
            }
            flash("Experiment will be loaded in the background {$message}")->success();
        }

        $experiment = $createExperimentAction($validated, $sheet);
        if ($request->input('files-next', false)) {
            $showOverview = $request->input('show-overview', false);
            return redirect(route('projects.folders.index', [$project, 'show-overview' => $showOverview]));
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
