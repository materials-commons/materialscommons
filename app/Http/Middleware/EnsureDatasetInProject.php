<?php

namespace App\Http\Middleware;

use App\Models\Dataset;
use App\Traits\GetRequestParameterId;
use Closure;

class EnsureDatasetInProject
{
    use GetRequestParameterId;

    /**
     * Ensure dataset is in project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $projectId = $this->getParameterId('project');
        if ($projectId != '') {
            $datasetId = $this->getParameterId('dataset');
            if ($datasetId != '') {
                $count = Dataset::where('id', $datasetId)->where('project_id', $projectId)->count();
                abort_if($count == 0, 404, 'No such dataset');
            }
        }
        return $next($request);
    }
}
