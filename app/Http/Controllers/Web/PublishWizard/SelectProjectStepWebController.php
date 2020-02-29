<?php

namespace App\Http\Controllers\Web\PublishWizard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class SelectProjectStepWebController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $projects = Project::where('owner_id', $user->id)->get();
        return view('app.publish.wizard.select_project', compact('projects'));
    }
}
