<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\CreateFilesAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Files\FileResource;
use App\Models\File;
use Illuminate\Http\Request;

class UploadFileApiController extends Controller
{
    public function __invoke(Request $request, CreateFilesAction $createFilesAction, $projectId, $directoryId)
    {
        $validated = $request->validate([
            'files.*' => 'required|file',
        ]);

        $dir = File::findOrFail($directoryId);

        return FileResource::collection(collect($createFilesAction($projectId, $dir, $validated['files'])));
    }
}
