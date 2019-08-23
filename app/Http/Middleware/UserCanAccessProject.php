<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

class UserCanAccessProject
{
    /**
     * Make sure user has access to the project. $next is called if no project route, or project_id parameter
     * is found.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $projectId = $this->getProjectId($request);
        if ($projectId != '') {
            $count = auth()->user()->projects()->where('project_id', $projectId)->count();
            abort_unless($count == 1, 404, 'No such project');
        }
        return $next($request);
    }

    private function getProjectId(Request $request)
    {
        $projectId = $request->route('project');
        if (gettype($projectId) == "object") {
            $projectId = $projectId->id;
        } elseif ($projectId == '') {
            $projectId = $request->get('project_id');
        }
        return $projectId;
    }
}
