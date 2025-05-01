<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\MoveFileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Files\MoveFileRequest;
use App\Http\Resources\Files\FileResource;
use App\Models\File;
use App\Services\AuthService;

class MoveFileApiController extends Controller
{
    public function __invoke(MoveFileRequest $request, MoveFileAction $moveFileAction, $fileId)
    {
        $toDirectoryId = $request->input('directory_id');
        $toDir = File::findOrFail($toDirectoryId);
        $f = File::withCommon()->findOrFail($fileId);
        $user = auth()->user();
        if (!AuthService::userCanAccessProjectId($user, $toDir->project_id)) {
            abort(403);
        }

        $file = $moveFileAction($f, $toDir, $user);
        return new FileResource($file);
    }
}
