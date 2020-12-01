<?php

namespace App\Http\Queries\Datasets;

use App\Models\Dataset;
use App\Models\Project;
use Illuminate\Http\Request;

class AllDatasetsForProjectQuery extends DatasetsQueryBuilder
{
    public function __construct(Project $project, ?Request $request = null)
    {
        $query = Dataset::with(['owner'])
                        ->withCounts()
                        ->where('project_id', $project->id);
        parent::__construct($query, $request);
    }
}