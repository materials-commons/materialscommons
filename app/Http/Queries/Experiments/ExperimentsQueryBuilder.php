<?php

namespace App\Http\Queries\Experiments;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ExperimentsQueryBuilder extends QueryBuilder
{
    public function __construct(Builder $builder, ?Request $request = null)
    {
        parent::__construct($builder, $request);
        $this->allowedFields('name', 'id', 'uuid', 'description')
             ->allowedIncludes(['activities', 'entities']);
    }
}