<?php

namespace App\Http\Queries\Directories;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class DirectoriesQueryBuilder extends QueryBuilder
{
    public function __construct($builder, ?Request $request = null)
    {
        parent::__construct($builder, $request);
        $this->allowedFields(['name', 'id', 'uuid', 'description'])
             ->allowedIncludes(['entities', 'activities', 'projects']);
    }
}
