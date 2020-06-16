<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Etl\GetFileByPathAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Files\GetFileByPathRequest;
use App\Http\Resources\Files\FileResource;

class GetFileByPathApiController extends Controller
{
    public function __invoke(GetFileByPathRequest $request, GetFileByPathAction $getFileByPathAction)
    {
        $validated = $request->validated();
        $file = $getFileByPathAction->execute($validated['project_id'], $validated['path']);
        abort_if(is_null($file), 404, "No such file");
        return new FileResource($file);
    }
}
