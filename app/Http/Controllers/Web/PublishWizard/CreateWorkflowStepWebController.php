<?php

namespace App\Http\Controllers\Web\PublishWizard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class CreateWorkflowStepWebController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $project = Project::where('name', 'Published Datasets Project')
                          ->where('owner_id', $user->id)->first();
        $workflowcode = "Heat Treat?(yes, right)->Heat Treat 4h/200c(right)->SEM(right)->Analyze\nHeat Treat?(no)->SEM->Analyze";
        return view('app.publish.wizard.create_workflow', compact('project', 'workflowcode'));
    }
}
