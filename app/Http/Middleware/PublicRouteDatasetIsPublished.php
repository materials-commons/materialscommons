<?php

namespace App\Http\Middleware;

use App\Models\Dataset;
use App\Traits\GetRequestParameterId;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use function abort_if;
use function is_null;

class PublicRouteDatasetIsPublished
{
    use GetRequestParameterId;

    /*
     * When a dataset is accessed from the /public route make sure that the dataset is published.
     */
    public function handle(Request $request, Closure $next)
    {
        $datasetId = $this->getParameterId('dataset');
        if ($datasetId == '') {
            return $next($request);
        }

        $routeUri = $request->route()->uri;
        if (Str::startsWith($routeUri, "public")) {
            $dataset = Dataset::findOrFail($datasetId);
            abort_if(is_null($dataset->published_at), 404, 'No such dataset');
        }

        return $next($request);
    }
}
