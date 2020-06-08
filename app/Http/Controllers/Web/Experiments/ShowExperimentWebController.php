<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;

class ShowExperimentWebController extends Controller
{
    public function __invoke($projectId, $experimentId)
    {
        $project = Project::with('experiments')->findOrFail($projectId);
        $experiment = Experiment::with('workflows')->findOrFail($experimentId);
        $excelFilesCount = $project->files()
                                   ->where('mime_type',
                                       "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
                                   ->where('current', true)
                                   ->count();

        // Datatables does case-insensitive sorting. The database is returning case sensitive, so
        // create a case insensitive list of the workflow items
        $workflows = $experiment->workflows->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);

        return view('app.projects.experiments.show', compact('project', 'experiment', 'workflows', 'excelFilesCount'));
    }
}
