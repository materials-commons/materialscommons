<?php

namespace App\Http\Controllers\Web\Files;

use App\Actions\Files\CreateFilesAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadFilesWebController extends Controller
{
    public function __invoke(Request $request, CreateFilesAction $createFilesAction, $projectId, $directoryId)
    {
        $validated = $request->validate([
            'files.*' => 'required|file',
        ]);

        $createFilesAction($projectId, $directoryId, $validated['files']);
    }
}
