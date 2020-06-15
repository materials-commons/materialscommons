<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\CreateFilesAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Files\FileResource;
use Illuminate\Http\Request;

class UploadFileApiController extends Controller
{
    public function __invoke(Request $request, CreateFilesAction $createFilesAction, $projectId, $directoryId)
    {
        $validated = $request->validate([
            'files.*' => 'required|file',
        ]);

        return FileResource::collection(collect($createFilesAction($projectId, $directoryId, $validated['files'])));
    }
}
