<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\CreateFilesAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Files\FileResource;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;

class UploadFileApiController extends Controller
{
    public function __invoke(Request $request, CreateFilesAction $createFilesAction, $projectId, $directoryId)
    {
        $validated = $request->validate([
            'files.*' => 'required|file',
        ]);

        $dir = File::findOrFail($directoryId);
        $project = Project::findOrFail($projectId);

        return FileResource::collection(collect($createFilesAction($project, $dir, $validated['files'], 'api')));
    }
}
