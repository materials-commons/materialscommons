<?php

namespace App\Http\Controllers\Api\Directories;

use App\Actions\Directories\CopyDirectoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Directories\MoveDirectoryRequest;
use App\Http\Resources\Directories\DirectoryResource;
use App\Models\File;
use App\Services\AuthService;

class CopyDirectoryApiController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(MoveDirectoryRequest $request, CopyDirectoryAction $copyDirectoryAction, $directoryId)
    {
        $toDirectoryId = $request->input('to_directory_id');
        $toDir = File::findOrFail($toDirectoryId);
        $fromDir = File::findOrFail($directoryId);
        $user = auth()->user();

        if (!AuthService::userCanAccessProjectId($user, $fromDir->project_id)) {
            abort(403);
        }

        if (!AuthService::userCanAccessProjectId($user, $toDir->project_id)) {
            abort(403);
        }

        $copyDirectoryAction->execute($fromDir, $toDir, $user);
        $toDir->refresh();
        return new DirectoryResource($toDir);
    }
}
