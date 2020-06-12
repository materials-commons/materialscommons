<?php

namespace App\Http\Queries\Projects;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;

class AllProjectsForUserQuery extends ProjectsQueryBuilder
{
    public function __construct(?Request $request = null)
    {
        $query = auth()->user()->projects()->with(['rootDir'])->getQuery();
        parent::__construct($query, $request);
        $this->allowedFilters('name', AllowedFilter::exact('project_id'));
    }
}
