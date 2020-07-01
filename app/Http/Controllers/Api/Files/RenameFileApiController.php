<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\RenameFileAction;
use App\Http\Controllers\Controller;
use App\Http\Queries\Traits\GetFileQuery;
use App\Http\Requests\Files\RenameFileRequest;
use App\Http\Resources\Files\FileResource;

class RenameFileApiController extends Controller
{
    use GetFileQuery;

    public function __invoke(RenameFileRequest $request, RenameFileAction $renameFileAction, $fileId)
    {
        $name = $request->input('name');
        $file = $renameFileAction($this->findOrFail($fileId), $name);
        return new FileResource($file);
    }
}
