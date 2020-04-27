<?php

namespace App\Http\Controllers\Api\Files;

use App\Http\Controllers\Controller;
use App\Models\File;

class DownloadFileApiController extends Controller
{
    public function __invoke($projectId, File $file)
    {
        return response()->download($file->mcfsPath(), $file->name);
    }
}
