<?php

namespace App\Http\Controllers\Web\PublishWizard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChooseCreateOrSelectProjectStepWebController extends Controller
{
    public function __invoke(Request $request)
    {
        $projects = auth()->user()->projects()->get();
        if ($projects->isEmpty()) {
            return view('app.publish.wizard.create_project');
        }
        return view('app.publish.wizard.create_or_select_project');
    }
}
