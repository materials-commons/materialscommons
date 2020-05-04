<?php

namespace App\Http\Middleware;

use App\Models\Activity;
use App\Traits\GetRequestParameterId;
use Closure;

class ActivityInProject
{
    use GetRequestParameterId;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $projectId = $this->getParameterId('project');
        if ($projectId == '') {
            return $next($request);
        }

        $activityId = $this->getParameterId('activity');
        if ($activityId == '') {
            return $next($request);
        }

        $count = Activity::where('id', $activityId)
                         ->where('project_id', $projectId)
                         ->count();
        abort_if($count == 0, 404, 'No such activity');

        return $next($request);
    }
}
