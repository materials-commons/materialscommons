<?php

namespace App\Http\Controllers\Web\Files;

use App\Actions\Files\RenameFileAction;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;

class UpdateRenameFileWebController extends Controller
{
    public function __invoke(Request $request, RenameFileAction $renameFileAction, Project $project, File $file)
    {
        $name = "";
        $file = $renameFileAction($file, $name);
        redirect(route('projects.files.show', [$project, $file]));
    }
}
