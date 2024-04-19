<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\CreateFileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Files\CreateFileRequest;
use App\Http\Resources\Files\FileResource;
use App\Models\File;

class CreateFileApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\Files\CreateFileRequest  $request
     * @param  \App\Actions\Files\CreateFileAction  $createFileAction
     *
     * @return \App\Http\Resources\Files\FileResource
     */
    public function __invoke(CreateFileRequest $request, CreateFileAction $createFileAction)
    {
        $validated   = $request->validated();
        $description = '';
        if (array_key_exists('description', $validated)) {
            $description = $validated['description'];
        }

        $dir = File::findOrFail($validated["directory_id"]);

        $file = $createFileAction($validated["project_id"], $dir, $description, $validated["file"]);

        return new FileResource($file);
    }
}
