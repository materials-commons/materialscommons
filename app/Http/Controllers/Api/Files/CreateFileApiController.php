<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\CreateFileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Files\CreateFileRequest;

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
        $validated = $request->validated();
        $request->file->save("bob");

        //        $file = $createFileAction($validated["project_id"], $validated["directory_id"], $validated["file"]);
        //        return new FileResource($file);
    }
}
