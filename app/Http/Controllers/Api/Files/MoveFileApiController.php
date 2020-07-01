<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\MoveFileAction;
use App\Http\Controllers\Controller;
use App\Http\Queries\Traits\GetFileQuery;
use App\Http\Requests\Files\MoveFileRequest;
use App\Http\Resources\Files\FileResource;
use App\Models\File;

class MoveFileApiController extends Controller
{
    public function __invoke(MoveFileRequest $request, MoveFileAction $moveFileAction, $fileId)
    {
        $toDirectoryId = $request->input('directory_id');
        $file = $moveFileAction(File::withCommon()->findOrFail($fileId), $toDirectoryId);
        return new FileResource($file);
    }
}
