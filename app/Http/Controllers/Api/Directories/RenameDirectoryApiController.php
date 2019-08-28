<?php

namespace App\Http\Controllers\Api\Directories;

use App\Actions\Directories\RenameDirectoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Directories\RenameDirectoryRequest;
use App\Http\Resources\Directories\DirectoryResource;

class RenameDirectoryApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\Directories\RenameDirectoryRequest  $request
     * @param  \App\Actions\Directories\RenameDirectoryAction  $renameDirectoryAction
     * @param $directoryId
     *
     * @return \App\Http\Resources\Directories\DirectoryResource
     */
    public function __invoke(RenameDirectoryRequest $request, RenameDirectoryAction $renameDirectoryAction, $directoryId)
    {
        $directory = $renameDirectoryAction($directoryId, $request->input('name'));

        return new DirectoryResource($directory);
    }
}