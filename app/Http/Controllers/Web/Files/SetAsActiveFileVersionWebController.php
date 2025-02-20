<?php

namespace App\Http\Controllers\Web\Files;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;

class SetAsActiveFileVersionWebController extends Controller
{
    public function __invoke(Project $project, File $file)
    {
        if ($file->current) {
            // Already active version nothing to do
            return redirect(route('projects.files.show', [$project, $file]));
        }

        $file->setAsActiveFile();

        return redirect(route('projects.files.show', [$project, $file]));
    }
}
