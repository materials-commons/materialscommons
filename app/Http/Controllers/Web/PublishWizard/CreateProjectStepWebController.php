<?php

namespace App\Http\Controllers\Web\PublishWizard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateProjectStepWebController extends Controller
{
    public function __invoke(Request $request)
    {
        $projectsCount = auth()->user()->projects()->count();
        return view('app.publish.wizard.create_project', compact('projectsCount'));
    }
}
