<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\CreateFileAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Files\FileResource;
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
        $createFileAction = new CreateFileAction();
        return FileResource::collection(collect([$createFileAction($projectId, $directoryId, '', $files[0], $name)]));
    }
}
