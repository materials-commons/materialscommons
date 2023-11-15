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
            'files.*'    => 'required|file',
            'experiment' => 'nullable|integer',
            'sample'     => 'nullable|string',
        ]);

        ray("experiment {$validated['experiment']}");
        ray("sample {$validated['sample']}");

        $createFilesAction($projectId, $directoryId, $validated['files']);
    }
}
