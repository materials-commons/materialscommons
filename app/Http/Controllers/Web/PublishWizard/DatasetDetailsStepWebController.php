<?php

namespace App\Http\Controllers\Web\PublishWizard;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class DatasetDetailsStepWebController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $project = Project::where('name', 'Published Datasets Project')
                          ->where('owner_id', $user->id)->first();
        if (is_null($project)) {
            $project = Project::create([
                'name'     => 'Published Datasets Project',
                'owner_id' => auth()->user()->id,
            ]);
        }
        $communities = collect();
        return view('app.publish.wizard.dataset_details', compact('project', 'communities'));
    }
}
