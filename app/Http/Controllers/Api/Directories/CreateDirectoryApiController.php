<?php

namespace App\Http\Controllers\Api\Directories;

use App\Actions\Directories\CreateDirectoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Directories\CreateDirectoryRequest;
use App\Http\Resources\Directories\DirectoryResource;

class CreateDirectoryApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  CreateDirectoryRequest  $request
     * @param  CreateDirectoryAction  $createDirectoryAction
     * @return DirectoryResource
     */
    public function __invoke(CreateDirectoryRequest $request, CreateDirectoryAction $createDirectoryAction)
    {
        $validated = $request->validated();
        $directory = $createDirectoryAction->execute($validated, auth()->id());
        return new DirectoryResource($directory);
    }
}
