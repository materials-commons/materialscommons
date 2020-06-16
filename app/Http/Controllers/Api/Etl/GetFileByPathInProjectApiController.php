<?php

namespace App\Http\Controllers\Api\Etl;

use App\Actions\Etl\GetFileByPathAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Etl\GetFileByPathRequest;

class GetFileByPathInProjectApiController extends Controller
{
    public function __invoke(GetFileByPathRequest $request, GetFileByPathAction $getFileByPathAction)
    {
        $validated = $request->validated();
        $file = $getFileByPathAction->execute($validated['project_id'], $validated['path']);
        if ($file === null) {
            return response()->json(['error' => 'not found'], 404);
        }
        return $file;
    }
}
