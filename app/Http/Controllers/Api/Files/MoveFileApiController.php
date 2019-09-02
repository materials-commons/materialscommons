<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\MoveFileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Files\MoveFileRequest;
use App\Http\Resources\Files\FileResource;
use App\Models\File;

class MoveFileApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  MoveFileRequest  $request
     * @param  MoveFileAction  $moveFileAction
     * @param  File  $file
     * @return FileResource
     */
    public function __invoke(MoveFileRequest $request, MoveFileAction $moveFileAction, File $file)
    {
        $toDirectoryId = $request->input('directory_id');
        $file          = $moveFileAction($file, $toDirectoryId);
        return new FileResource($file);
    }
}
