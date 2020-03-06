<?php

namespace App\Http\Controllers\Web\PublishWizard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChooseCreateOrSelectProjectStepWebController extends Controller
{
    public function __invoke(Request $request)
    {
        $projectsCount = auth()->user()->projects()->count();
        if ($projectsCount == 0) {
            return view('app.publish.wizard.create_project', compact('projectsCount'));
        }
        return view('app.publish.wizard.create_or_select_project');
    }
}
