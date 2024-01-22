<?php

namespace App\Http\Controllers\Web\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use function auth;
use function route;

class UnmarkProjectAsActiveWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $target = $request->input("target");
        $user = auth()->user();
        $user->removeFromActiveProjects($project);
        $route = route('projects.mark-as-active', [$project, 'target' => $target]);
        return <<<RESP
        <a hx-get="{$route}" hx-target="#{$target}"
               class="btn btn-success float-right cursor-pointer">Mark As Active Project</a>
        RESP;
    }
}
