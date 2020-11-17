<?php

namespace App\Http\Controllers\Web\Files;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;

class ShowFileExperimentsWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $experimentId = $request->route('experiment');
        $experiment = null;

        $fileId = $request->route('file');
        $file = File::withCount('entities', 'activities', 'experiments')
                    ->with('experiments')
                    ->findOrFail($fileId);
        $previousVersions = $file->previousVersions()->get();

        if ($experimentId !== null) {
            $experiment = Experiment::find($experimentId);
        }
        return view('app.files.show', compact('project', 'file', 'experiment', 'previousVersions'));
    }
}
