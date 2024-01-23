<?php

namespace App\Http\Controllers\Web\Sheets;

use App\Actions\GoogleSheets\GetGoogleSheetNameAction;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ResolveGoogleSheetTitleWebController extends Controller
{
    public function __invoke(Request $request, GetGoogleSheetNameAction $getGoogleSheetNameAction, Project $project)
    {
        $sheetUrl = $request->get("sheet_url");
        if (blank($sheetUrl)) {
            return '';
        }

        $title = $getGoogleSheetNameAction->execute($sheetUrl);
        if (is_null($title)) {
            return '';
        }

        return "<span>Google Sheet Title: {$title}</span><br/>";
    }
}
