<?php

namespace App\Http\Controllers\Web\PublishWizard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class SelectProjectStepWebController extends Controller
{
    public function __invoke(Request $request)
    {
        $projects = Project::all();
        return view('app.publish.wizard.select_project', compact('projects'));
    }
}
