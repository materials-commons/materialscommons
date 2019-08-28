<?php

namespace App\Http\Controllers\Api\Directories;

use App\Actions\Directories\MoveDirectoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Directories\MoveDirectoryRequest;
use App\Http\Resources\Directories\DirectoryResource;

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
        $directory = $moveDirectoryAction($directoryId, $request->input('to_directory_id'));

        return new DirectoryResource($directory);
    }
}
