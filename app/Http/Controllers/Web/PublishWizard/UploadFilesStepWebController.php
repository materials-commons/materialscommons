<?php

namespace App\Http\Controllers\Web\PublishWizard;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;

class UploadFilesStepWebController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $project = Project::where('name', 'Published Datasets Project')
                          ->where('owner_id', $user->id)->first();
        $directory = File::where('project_id', $project->id)->where('name', '/')->first();
        return view('app.publish.wizard.upload_files', compact('project', 'directory'));
    }
}