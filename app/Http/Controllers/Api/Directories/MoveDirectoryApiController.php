<?php

namespace App\Http\Controllers\Api\Directories;

use App\Actions\Directories\MoveDirectoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Directories\MoveDirectoryRequest;
use App\Http\Resources\Directories\DirectoryResource;
use App\Models\File;
use App\Services\AuthService;
use function abort;

class MoveDirectoryApiController extends Controller
{
    /**
     * Move a directory and recursively all its children
     *
     * @param  \App\Http\Requests\Directories\MoveDirectoryRequest  $request
     *
     * @param  \App\Actions\Directories\MoveDirectoryAction  $moveDirectoryAction
     * @param $directoryId
     *
     * @return \App\Http\Resources\Directories\DirectoryResource
     */
    public function __invoke(MoveDirectoryRequest $request, MoveDirectoryAction $moveDirectoryAction, $directoryId)
    {
        $toDirectoryId = $request->input('to_directory_id');
        $toDir = File::findOrFail($toDirectoryId);
        $user = auth()->user();

        if (!AuthService::userCanAccessProjectId($user, $toDir->project_id)) {
            abort(403);
        }

        $directory = $moveDirectoryAction($directoryId, $toDirectoryId, $user);
        return new DirectoryResource($directory);
    }
}
