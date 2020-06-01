<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\UpdateFileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Files\UpdateFileRequest;
use App\Http\Resources\Files\FileResource;
use App\Models\File;

class UpdateFileApiController extends Controller
{
    public function __invoke(UpdateFileRequest $request, UpdateFileAction $updateFileAction, File $file)
    {
        $validated = $request->validated();
        unset($validated['project_id']);
        $file = $updateFileAction($validated, $file);

        return new FileResource($file);
    }
}
