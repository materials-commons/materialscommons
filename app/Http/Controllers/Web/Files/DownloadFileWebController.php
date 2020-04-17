<?php

namespace App\Http\Controllers\Web\Files;

use App\Http\Controllers\Controller;
use App\Models\File;

class DownloadFileWebController extends Controller
{
    public function __invoke($projectId, File $file)
    {
        return response()->download($file->mcfsPath(), $file->name);
    }
}
