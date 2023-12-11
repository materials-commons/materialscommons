<?php

namespace App\Http\Middleware;

use App\Models\Project;
use App\Traits\GetRequestParameterId;
use App\Traits\Projects\CanAccessProject;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use function auth;

class UserCanAccessProject
{
    use CanAccessProject;
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

    private function isPublicPath(): bool
    {
        return Str::contains(request()->url(), "/public");
    }

    private function isPublicProject($projectId): bool
    {
        $count = Project::where('id', $projectId)->where('is_public', true)->count();
        return $count == 1;
    }

    private function canAccessPrivateProject($projectId): bool
    {
        if ($this->userIsProjectMember($projectId, auth()->id())) {
            return true;
        }

        return $this->userIsProjectAdmin($projectId, auth()->id());
    }
}
