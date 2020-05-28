<?php

namespace App\Http\Queries\Datasets;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class DatasetsQueryBuilder extends QueryBuilder
{
    public function __construct($builder, ?Request $request = null)
    {
        parent::__construct($builder, $request);
        $this->allowedFields(['name', 'id', 'uuid', 'description', 'summary']);
        $this->allowedIncludes(['activities', 'entities', 'files', 'workflows', 'comments', 'experiments']);
    }
}