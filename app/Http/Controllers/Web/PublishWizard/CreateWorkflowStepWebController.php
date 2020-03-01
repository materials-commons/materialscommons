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
        return view('app.publish.wizard.create_workflow', compact('project'));
    }
}
