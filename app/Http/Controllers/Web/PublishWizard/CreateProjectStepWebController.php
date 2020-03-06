<?php

namespace App\Http\Controllers\Web\PublishWizard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateProjectStepWebController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('public.publish.wizard.create_project');
    }
}
