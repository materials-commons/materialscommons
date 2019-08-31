<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\CreateFileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Files\CreateFileRequest;

class CreateFileApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\Files\CreateFileRequest  $request
     * @param  \App\Actions\Files\CreateFileAction  $createFileAction
     *
     * @return void
     */
    public function __invoke(CreateFileRequest $request, CreateFileAction $createFileAction)
    {
        $validated = $request->validated();
        $createFileAction($request);
    }
}
