<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\CreateFileAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Files\FileResource;
use App\Models\File;
use App\Models\Project;
use App\Models\ScriptTrigger;
use Illuminate\Http\Request;

class UploadFileNamedApiController extends Controller
{
    public function __invoke(Request $request, $projectId, $directoryId, $name)
    {
        $validated = $request->validate([
            'files.*' => 'required|file',
        ]);

        $files = $validated['files'];
        abort_unless(sizeof($files) == 1, 403);

        $dir = File::findOrFail($directoryId);
        $project = Project::findOrFail($projectId);

        $triggers = ScriptTrigger::getProjectTriggers($project);
        $createFileAction = new CreateFileAction($triggers);

        return FileResource::collection(collect([$createFileAction($project, $dir, '', $files[0], $name)]));
    }
}
