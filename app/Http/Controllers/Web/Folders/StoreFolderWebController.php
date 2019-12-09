<?php

namespace App\Http\Controllers\Web\Folders;

use App\Actions\Directories\CreateDirectoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Directories\CreateDirectoryRequest;
use App\Models\Project;

class StoreFolderWebController extends Controller
{

    public function __invoke(
        CreateDirectoryRequest $request, CreateDirectoryAction $createDirectoryAction, Project $project
    ) {
        $validated = $request->validated();
        $directory = $createDirectoryAction($validated);
        return redirect(route('projects.folders.show', [$project, $directory]));
    }
}
