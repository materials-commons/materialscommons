<?php

namespace App\Http\Queries\Experiments;

use App\Models\Experiment;
use Illuminate\Http\Request;

class AllExperimentsForProjectQuery extends ExperimentsQueryBuilder
{
    public function __construct($projectId, ?Request $request = null)
    {
        $query = Experiment::with(['owner'])
                           ->where('project_id', $projectId);
        parent::__construct($query, $request);
    }
}