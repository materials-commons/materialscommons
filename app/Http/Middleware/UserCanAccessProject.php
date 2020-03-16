<?php

namespace App\Http\Middleware;

use App\Traits\GetRequestParameterId;
use Closure;
use Illuminate\Http\Request;

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

        if (auth()->user()->is_admin) {
            return $next($request);
        }

        $count = auth()->user()->projects()->where('project_id', $projectId)->count();
        abort_unless($count == 1, 404, 'No such project');

        return $next($request);
    }
}
