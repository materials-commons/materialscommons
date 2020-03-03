<?php

namespace App\Http\Controllers\Web\PublishWizard;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use Illuminate\Http\Request;

class ReviewDatasetAndPublishStepWebController extends Controller
{
    public function __invoke(Request $request, Project $project, Dataset $dataset)
    {
        return view('app.publish.wizard.review_dataset', compact('project', 'dataset'));
    }
}
