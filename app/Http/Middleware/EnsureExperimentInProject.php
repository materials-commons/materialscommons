<?php

namespace App\Http\Middleware;

use App\Models\Experiment;
use App\Traits\GetRequestParameterId;
use Closure;

class EnsureExperimentInProject
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
        if ($projectId != '') {
            $experimentId = $this->getParameterId('experiment');
            if ($experimentId != '') {
                $count = Experiment::where('id', $experimentId)->where('project_id', $projectId)->count();
                abort_if($count == 0, 404, 'No such dataset');
            }
        }
        return $next($request);
    }
}
