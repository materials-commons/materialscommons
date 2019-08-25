<?php

namespace App\Http\Controllers\Api\Entities;

use App\Http\Controllers\Controller;
use App\Http\Queries\Entities\SingleEntityQuery;
use App\Http\Resources\Entities\EntityResource;

class ShowEntityApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  SingleEntityQuery  $query
     * @return EntityResource
     */
    public function __invoke(SingleEntityQuery $query)
    {
        $data = $query->get();
        return new EntityResource($data[0]);
    }
}
