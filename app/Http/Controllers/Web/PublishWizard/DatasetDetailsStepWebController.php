<?php

namespace App\Http\Controllers\Web\PublishWizard;

use App\Actions\Projects\CreateProjectAction;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class DatasetDetailsStepWebController extends Controller
{
    public function __invoke(CreateProjectAction $createProjectAction, Request $request, Project $project)
    {
        $communities = collect();
        return view('app.publish.wizard.dataset_details', compact('project', 'communities'));
    }
}
