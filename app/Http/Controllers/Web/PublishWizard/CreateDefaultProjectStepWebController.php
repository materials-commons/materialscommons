<?php

namespace App\Http\Controllers\Web\PublishWizard;

use App\Actions\Projects\CreateProjectAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateProjectStepWebController extends Controller
{
    public function __invoke(Request $request, CreateProjectAction $createProjectAction)
    {
        $results = $createProjectAction(['name' => 'Published Datasets Project']);
        $project = $results['project'];
        return redirect(route('public.publish.wizard.dataset_details', [$project]));
    }
}
