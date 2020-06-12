<?php

namespace App\Http\Queries\Projects;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectsQueryBuilder extends QueryBuilder
{
    public function __construct($builder, ?Request $request = null)
    {
        parent::__construct($builder, $request);
        $this->allowedFields(['name', 'id', 'uuid', 'description', 'is_active'])
             ->allowedIncludes(['activities', 'entities', 'files', 'workflows', 'experiments']);
    }
}
