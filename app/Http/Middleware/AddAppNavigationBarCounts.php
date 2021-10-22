<?php

namespace App\Http\Middleware;

use App\Models\Project;
use App\Traits\GetRequestParameterId;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class AddAppNavigationBarCounts
{
    use GetRequestParameterId;

    public function handle(Request $request, Closure $next)
    {
        $projectId = $this->getParameterId('project');
        if (!blank($projectId)) {
            // If there is a project then we are in the project nav and don't need to compute
            // this count.
            return $next($request);
        }

        View::share('nav_trash_count', Project::getDeletedTrashCountForUser(auth()->id()));
        return $next($request);
    }
}
