<?php

namespace App\Http\Controllers\Api\Projects;

use App\Http\Controllers\Controller;
use App\Http\Resources\Projects\ProjectResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexProjectsApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  Request  $request
     * @return AnonymousResourceCollection
     */
    public function __invoke(Request $request)
    {
        $query = auth()->user()->projects()->getQuery();

        $data = QueryBuilder::for($query)
            ->allowedFilters('name', AllowedFilter::exact('project_id'))
            ->withCount(['activities', 'entities', 'files'])
            ->jsonPaginate();

        return ProjectResource::collection($data);
    }
}
