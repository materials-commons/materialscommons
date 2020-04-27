<?php

namespace App\Http\Controllers\Api\Files;

use App\Actions\Files\DeleteFileAction;
use App\Http\Controllers\Controller;
use App\Models\File;

class DeleteFileApiController extends Controller
{
    public function __invoke(DeleteFileAction $deleteFileAction, $projectId, File $file)
    {
        $deleteFileAction($file);
    }
}
