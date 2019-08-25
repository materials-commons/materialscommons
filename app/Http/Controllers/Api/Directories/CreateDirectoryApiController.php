<?php

namespace App\Http\Controllers\Api\Directories;

use App\Actions\Directories\CreateDirectoryAction;
use App\Http\Requests\Directories\CreateDirectoryRequest;
use App\Http\Resources\Directories\DirectoryResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CreateDirectoryApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  CreateDirectoryRequest  $request
     * @param  CreateDirectoryAction  $createDirectoryAction
     * @return void
     */
    public function __invoke(CreateDirectoryRequest $request, CreateDirectoryAction $createDirectoryAction)
    {
        $validated = $request->validated();
        $directory = $createDirectoryAction($validated);
        return new DirectoryResource($directory);
    }
}
