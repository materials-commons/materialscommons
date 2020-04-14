<?php

namespace App\Http\Queries\Datasets;

use App\Models\Project;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class AllDatasetsForProjectQuery extends QueryBuilder
{
    public function __construct(Project $project, ?Request $request = null)
    {
        $query = $project->datasets()->getQuery();
        parent::__construct($query, $request);
        $this->allowedFields(['name', 'id', 'uuid', 'description', 'summary']);
        $this->allowedIncludes(['activities', 'entities', 'files', 'workflows', 'comments', 'experiments']);
    }
}