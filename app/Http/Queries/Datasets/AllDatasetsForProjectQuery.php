<?php

namespace App\Http\Queries\Datasets;

use App\Models\Project;
use Illuminate\Http\Request;

class AllDatasetsForProjectQuery extends DatasetsQueryBuilder
{
    public function __construct(Project $project, ?Request $request = null)
    {
        $query = $project->datasets()->getQuery();
        parent::__construct($query, $request);
    }
}