<?php

namespace App\Http\Controllers\Web\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class MarkProjectAsActiveWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $target = $request->input("target");
        $user = auth()->user();
        $user->addToActiveProjects($project);
        $route = route('projects.unmark-as-active', [$project, 'target' => $target]);
        return <<<RESP
        <a hx-get="{$route}" hx-target="#{$target}"
               class="btn btn-info float-right cursor-pointer">Remove From Active Projects</a>
        RESP;
    }
}
