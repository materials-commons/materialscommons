<?php

namespace App\Http\Controllers\Api\Activities;

use App\Http\Controllers\Controller;
use App\Http\Queries\Activities\SingleActivityQuery;
use App\Http\Resources\Activities\ActivityResource;

class ShowActivityApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  SingleActivityQuery  $query
     *
     * @return ActivityResource
     */
    public function __invoke(SingleActivityQuery $query)
    {
        $data = $query->get();
        return new ActivityResource($data[0]);
    }
}
