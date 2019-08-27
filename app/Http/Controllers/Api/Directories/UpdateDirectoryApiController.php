<?php

namespace App\Http\Controllers\Api\Directories;

use App\Actions\Directories\UpdateDirectoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Directories\UpdateDirectoryRequest;
use App\Http\Resources\Directories\DirectoryResource;

class UpdateDirectoryApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  UpdateDirectoryRequest  $request
     * @param  UpdateDirectoryAction  $updateDirectoryAction
     * @param $directoryId
     * @return DirectoryResource
     */
    public function __invoke(
        UpdateDirectoryRequest $request,
        UpdateDirectoryAction $updateDirectoryAction,
        $directoryId
    )
    {
        $validated = $request->validated();
        $directory = $updateDirectoryAction($validated, $directoryId);
        return new DirectoryResource($directory);
    }
}
