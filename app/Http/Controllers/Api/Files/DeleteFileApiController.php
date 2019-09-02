<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\DeleteFileAction;
use App\Http\Controllers\Controller;
use App\Models\File;

class DeleteFileApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Actions\Files\DeleteFileAction  $deleteFileAction
     * @param  \App\Models\File  $file
     * @return void
     */
    public function __invoke(DeleteFileAction $deleteFileAction, File $file)
    {
        $deleteFileAction($file);
    }
}
