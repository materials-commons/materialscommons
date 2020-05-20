<?php

namespace App\Http\Controllers\Web\PublishWizard;

use App\Actions\Projects\CreateProjectAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateDefaultProjectStepWebController extends Controller
{
    public function __invoke(Request $request, CreateProjectAction $createProjectAction)
    {
        $results = $createProjectAction->execute(['name' => 'Published Datasets Project'], auth()->id());
        $project = $results['project'];
        return redirect(route('public.publish.wizard.dataset_details', [$project]));
    }
}
