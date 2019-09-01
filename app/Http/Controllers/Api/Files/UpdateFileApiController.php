<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\UpdateFileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Files\UpdateFileRequest;
use App\Models\File;

class UpdateFileApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\Files\UpdateFileRequest  $request
     * @param  \App\Actions\Files\UpdateFileAction  $updateFileAction
     * @param  \App\Models\File  $file
     *
     * @return \App\Http\Controllers\Api\Files\FileResource
     */
    public function __invoke(UpdateFileRequest $request, UpdateFileAction $updateFileAction, File $file)
    {
        $validated = $request->validated();
        $file      = $updateFileAction($validated, $file);

        return new FileResource($file);
    }
}
