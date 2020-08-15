<?php

namespace App\Http\Controllers\Web\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ShowProjectDocumentsWebController extends Controller
{
    public function __invoke(Project $project)
    {
        return redirect(route('projects.documents.show.files', [$project]));
    }
}
