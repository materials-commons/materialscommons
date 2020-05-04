<?php

namespace App\Http\Middleware;

use App\Models\Entity;
use App\Traits\GetRequestParameterId;
use Closure;

class EntityInProject
{
    use GetRequestParameterId;

    /**
     * Ensure entity is in project.
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

        $entityId = $this->getParameterId('entity');
        if ($entityId == '') {
            return $next($request);
        }

        $count = Entity::where('id', $entityId)
                       ->where('project_id', $projectId)
                       ->count();
        abort_if($count == 0, 404, 'No such entity');

        return $next($request);
    }
}
