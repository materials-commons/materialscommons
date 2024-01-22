<?php

namespace App\Http\Controllers\Web\Sheets;

use App\Actions\GoogleSheets\GetGoogleSheetNameAction;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Sheet;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use function redirect;
use function route;

class AddGoogleSheetToProjectWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, GetGoogleSheetNameAction $getGoogleSheetNameAction, Project $project)
    {
        $sheeturl = $request->input("sheeturl");
        if (blank($sheeturl)) {
            return redirect(route('projects.files.sheets.index', [$project]));
        }

        $existingSheet = Sheet::where('url', $sheeturl)
                              ->where('project_id', $project->id)
                              ->first();
        if (!is_null($existingSheet)) {
            flash("Sheet with URL already added to project.")->success();
            return redirect(route('projects.files.sheets.index', [$project]));
        }

        $title = $getGoogleSheetNameAction->execute($sheeturl);
        if (blank($title)) {
            flash("Unable to resolve google sheet title.")->error();
            return redirect(route('projects.files.sheets.index', [$project]));
        }

        $sheet = new Sheet([
            'uuid'       => Uuid::uuid4()->toString(),
            'url'        => $sheeturl,
            'title'      => $title,
            'owner_id'   => auth()->id(),
            'project_id' => $project->id,
        ]);
        $sheet->save();

        flash("Google Sheet {$title} successfully added!")->success();
        return redirect(route('projects.files.sheets.index', [$project]));
    }
}
