<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Actions\Etl\GetFileByPathAction;
use App\Actions\Experiments\ReloadExperimentAction;
use App\Actions\GoogleSheets\CreateGoogleSheetAction;
use App\Helpers\PathHelpers;
use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\File;
use App\Models\Project;
use App\Models\Sheet;
use Illuminate\Http\Request;
use function auth;
use function flash;
use function is_null;
use function redirect;
use function route;

class ReloadExperimentWebController extends Controller
{
    public function __invoke(Request    $request, ReloadExperimentAction $reloadExperimentAction, Project $project,
                             Experiment $experiment)
    {
        $validated = $request->validate([
            'file_id'   => 'nullable|string',
            'file_path' => 'nullable|string',
            'sheet_url' => 'nullable|url',
            'sheet_id'  => 'nullable|integer'
        ]);

        $fileId = $request->get('file_id');
        $filePath = $request->get('file_path');
        $sheetUrl = $request->get('sheet_url');
        $sheetId = $request->get('sheet_id');

        if (!$this->onlySheetIdOrSheetUrlOrFilePathOrFileIdSpecified($validated)) {
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
            $experiment->update(['sheet_id' => $sheet->id, 'loaded_file_path' => null]);
        } elseif (!is_null($fileId)) {
            $file = File::with('directory')->where('id', $fileId)->first();
            $experiment->update([
                'loaded_file_path' => PathHelpers::joinPaths($file->directory->path, $file->name),
                'sheet_id'         => null,
            ]);
        } elseif (!is_null($filePath)) {
            $getByPath = new GetFileByPathAction();
            $file = $getByPath->execute($project->id, $filePath);
            if (!is_null($file)) {
                $fileId = $file->id;
                $experiment->update([
                    'loaded_file_path' => PathHelpers::joinPaths($file->directory->path, $file->name),
                    'sheet_id'         => null,
                ]);
            }
        }

        if ($reloadExperimentAction->execute($project, $experiment, $fileId, $sheetUrl, auth()->id())) {
            flash("Reloading experiment {$experiment->name} in background.")->success();
        } else {
            flash("Failed reloading, no changes made to experiment.")->error();
        }

        return redirect(route('projects.experiments.show', [$project, $experiment]));
    }

    private function onlySheetIdOrSheetUrlOrFilePathOrFileIdSpecified(mixed $validated)
    {
        $countNotNull = 0;
        if (isset($validated["file_path"])) {
            $countNotNull++;
        }

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
