<?php

namespace App\Http\Controllers\Web\Sheets;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Models\Sheet;
use App\Traits\FileType;
use Illuminate\Http\Request;

class IndexSheetsWebController extends Controller
{
    use FileType;

    public function __invoke(Request $request, Project $project)
    {
        $types = ["text/csv"];
        ray($this->excelTypes);
        foreach ($this->excelTypes as $excelType => $ignore) {
            ray("excelType = {$excelType}");
            $types[] = $excelType;
        }

        $files = File::with('directory')
                     ->where("project_id", $project->id)
                     ->whereIn("mime_type", $types)
                     ->where('current', true)
                     ->whereNull('dataset_id')
                     ->whereNull('deleted_at')
                     ->get();

        $sheets = Sheet::where("project_id", $project->id)
                       ->get();

        $merged = $files->merge($sheets);

        return view('app.projects.files.sheets', [
            'project' => $project,
            'sheets'  => $merged,
        ]);
    }
}
