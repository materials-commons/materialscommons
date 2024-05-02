<?php

namespace App\Http\Controllers\Web\Triggers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Script;
use Illuminate\Http\Request;

class CreateTriggerWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $scripts = Script::listForProject($project);
        return view('app.projects.triggers.create', compact('project', 'scripts'));
    }
}
