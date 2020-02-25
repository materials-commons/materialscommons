<?php

namespace App\Http\Controllers\Web\PublishWizard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DatasetDetailsStepWebController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('app.publish.wizard.dataset_details');
    }
}
