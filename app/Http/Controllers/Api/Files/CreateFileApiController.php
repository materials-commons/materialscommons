<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\CreateFileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Files\CreateFileRequest;
use App\Http\Resources\Files\FileResource;
use App\Models\File;
use App\Models\Project;
use App\Models\ScriptTrigger;

class CreateFileApiController extends Controller
{
    public function __invoke(CreateFileRequest $request)
    {
        $validated   = $request->validated();
        $description = '';
        if (array_key_exists('description', $validated)) {
            $description = $validated['description'];
        }

        $dir = File::findOrFail($validated["directory_id"]);
        $project = Project::findOrFail($validated["project_id"]);

        $triggers = ScriptTrigger::getProjectTriggers($project);
        $createFileAction = new CreateFileAction($triggers);

        $file = $createFileAction($project, $dir, $description, $validated["file"], 'api');

        return new FileResource($file);
    }
}
