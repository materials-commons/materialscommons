<?php

namespace App\Http\Controllers\Web\Projects\Documents;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ShowProjectDocumentsUnitsWebController extends Controller
{
    public function __invoke(Project $project)
    {
        return view('app.projects.show', compact('project'));
    }
}
