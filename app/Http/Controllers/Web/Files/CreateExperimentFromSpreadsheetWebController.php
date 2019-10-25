<?php

namespace App\Http\Controllers\Web\Files;

use App\Actions\Experiments\CreateExperimentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Files\CreateExperimentFromSpreadsheetRequest;
use App\Jobs\Etl\ProcessSpreadsheetJob;
use App\Models\File;
use App\Models\Project;

class CreateExperimentFromSpreadsheetWebController extends Controller
{
    public function __invoke(CreateExperimentFromSpreadsheetRequest $request, Project $project, File $file)
    {
        $createExperimentAction = new CreateExperimentAction();
        $validated = $request->validated();
        $validated['project_id'] = $project->id;
        $experiment = $createExperimentAction($validated);
        $ps = new ProcessSpreadsheetJob($project->id, $experiment->id, auth()->id(), $file->id);
        dispatch($ps);
        return view('app.files.show', compact('project', 'file'));
    }
}
