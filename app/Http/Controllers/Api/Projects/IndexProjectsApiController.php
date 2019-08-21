<?php

namespace App\Http\Controllers\Api\Projects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexProjectsApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $query = auth()->user()->projects()->getQuery();

        $data = QueryBuilder::for($query)
            ->allowedFilters('name', AllowedFilter::exact('project_id'))
            ->withCount(['activities', 'entities', 'files'])
            ->jsonPaginate();

        return $data;
    }
}
