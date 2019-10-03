<?php

namespace App\Http\Controllers\Web\Files;

use App\Actions\Experiments\CreateExperimentAction;
use App\Http\Controllers\Controller;
use App\Jobs\Etl\ProcessSpreadsheet;
use App\Models\File;
use App\Models\Project;
use App\Traits\PathFromUUID;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ImportSpreadsheetWebController extends Controller
{
    use PathFromUUID;

    public function __invoke(Request $request, CreateExperimentAction $createExperimentAction, Project $project,
        File $file)
    {
        $now = Carbon::now();
        $experiment = $createExperimentAction(['name' => "exp {$now->toDateString()}", 'project_id' => $project->id]);
        $ps = new ProcessSpreadsheet($project->id, $experiment->id, auth()->id(), $file->id);
        dispatch($ps);
//        $uuid = $file->uses_uuid ?? $file->uuid;
//        $uuidPath = $this->filePathFromUuid($uuid);
//        $importer = new EntityActivityImporter($project->id, $experiment->id, auth()->id());
//        Excel::import($importer, storage_path("app/{$uuidPath}"), null, \Maatwebsite\Excel\Excel::XLSX);
    }
}
