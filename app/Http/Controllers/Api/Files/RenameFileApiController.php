<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\RenameFileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Files\RenameFileRequest;
use App\Http\Resources\Files\FileResource;
use App\Models\File;

class RenameFileApiController extends Controller
{
    /**
     * Rename a file and all its previous versions
     *
     * @param  \App\Http\Requests\Files\RenameFileRequest  $request
     * @param  \App\Actions\Files\RenameFileAction  $renameFileAction
     * @param  \App\Models\File  $file
     * @return \App\Http\Resources\Files\FileResource
     */
    public function __invoke(RenameFileRequest $request, RenameFileAction $renameFileAction, File $file)
    {
        $name = $request->input('name');
        $file = $renameFileAction($file, $name);
        return new FileResource($file);
    }
}
