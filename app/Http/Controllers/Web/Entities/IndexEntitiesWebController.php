<?php

namespace App\Http\Controllers\Web\Entities;

use App\Http\Controllers\Controller;
use App\Models\Project;

class IndexEntitiesWebController extends Controller
{
    public function __invoke(Project $project)
    {
        return view('app.projects.entities.index', ['project' => $project]);
    }
}
