<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\MoveFileAction;
use App\Http\Controllers\Controller;
use App\Http\Queries\Traits\GetFileQuery;
use App\Http\Requests\Files\MoveFileRequest;
use App\Http\Resources\Files\FileResource;

class MoveFileApiController extends Controller
{
    use GetFileQuery;

    public function __invoke(MoveFileRequest $request, MoveFileAction $moveFileAction, $fileId)
    {
        $toDirectoryId = $request->input('directory_id');
        $file = $moveFileAction($this->findOrFail($fileId), $toDirectoryId);
        return new FileResource($file);
    }
}
