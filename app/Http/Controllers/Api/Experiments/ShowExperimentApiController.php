<?php

namespace App\Http\Controllers\Api\Experiments;

use App\Http\Controllers\Controller;
use App\Http\Queries\Experiments\SingleExperimentQuery;
use App\Http\Resources\Experiments\ExperimentResource;

class ShowExperimentApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Queries\Experiments\SingleExperimentQuery  $query
     *
     * @return \App\Http\Resources\Experiments\ExperimentResource
     */
    public function __invoke(SingleExperimentQuery $query)
    {
        $data = $query->get();

        return new ExperimentResource($data[0]);
    }
}
