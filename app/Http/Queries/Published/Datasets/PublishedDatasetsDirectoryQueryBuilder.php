<?php

namespace App\Http\Queries\Published\Datasets;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class PublishedDatasetsDirectoryQueryBuilder extends QueryBuilder
{
    public function __construct($subject, ?Request $request = null)
    {
        parent::__construct($subject, $request);
        $this->allowedFields(['name', 'id', 'uuid', 'description'])
            ->allowedIncludes(['entities', 'activities']);
    }
}