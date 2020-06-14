<?php

namespace App\Http\Middleware;

use App\Models\Project;
use App\Traits\GetRequestParameterId;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserCanAccessProject
{
    use GetRequestParameterId;

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
        $projectId = $this->getParameterId('project');
        if ($projectId == '') {
            return $next($request);
        }

        if ($this->isPublicPath()) {
            abort_unless($this->isPublicProject($projectId), 404, 'No such project');
            return $next($request);
        }

        if (optional(auth()->user())->is_admin) {
            return $next($request);
        }

        abort_unless($this->canAccessPrivateProject($projectId), 404, 'No such project');

        return $next($request);
    }

    private function isPublicPath()
    {
        return Str::contains(request()->url(), "/public");
    }

    private function isPublicProject($projectId)
    {
        $count = Project::where('id', $projectId)->where('is_public', true)->count();
        return $count == 1;
    }

    private function canAccessPrivateProject($projectId)
    {
        $count = auth()->user()->projects()->where('project_id', $projectId)->count();
        return $count == 1;
    }
}
