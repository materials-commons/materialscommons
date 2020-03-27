<?php

namespace App\Http\Controllers\Web\Workflows;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;
use App\Models\Workflow;

class ShowAttachWorkflowsToExperimentWebController extends Controller
{
    public function __invoke(Project $project, Experiment $experiment)
    {
        $workflows = Workflow::where('project_id', $project->id)
                             ->whereNotIn('id', function ($q) use ($experiment) {
                                 $q->select('workflow_id')->from('item2workflow')
                                   ->where('item_type', Experiment::class)
                                   ->where('item_id', $experiment->id);
                             })
                             ->get();
        return view('app.projects.experiments.workflows.attach', compact('project', 'experiment', 'workflows'));
    }
}
