<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\CopyFileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Files\MoveFileRequest;
use App\Http\Resources\Files\FileResource;
use App\Models\File;
use App\Services\AuthService;
use Illuminate\Http\Request;

class CopyFileApiController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(MoveFileRequest $request, CopyFileAction $copyFileAction, $fileId)
    {
        $toDirectoryId = $request->input('directory_id');
        $toDir = File::findOrFail($toDirectoryId);
        $f = File::withCommon()->findOrFail($fileId);
        $user = auth()->user();

        if (!AuthService::userCanAccessProjectId($user, $f->project_id)) {
            abort(403);
        }

        if (!AuthService::userCanAccessProjectId($user, $toDir->project_id)) {
            abort(403);
        }

        $copyFileAction->execute($f, $toDir, $user);
        $f->refresh();
        return new FileResource($f);
    }
}
