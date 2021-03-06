<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\RenameFileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Files\RenameFileRequest;
use App\Http\Resources\Files\FileResource;
use App\Models\File;

class RenameFileApiController extends Controller
{
    public function __invoke(RenameFileRequest $request, RenameFileAction $renameFileAction, $fileId)
    {
        $name = $request->input('name');
        $file = $renameFileAction(File::withCommon()->findOrFail($fileId), $name);
        return new FileResource($file);
    }
}
