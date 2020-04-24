<?php

namespace App\Http\Queries\Activities;

use App\Models\Activity;
use Illuminate\Http\Request;

class AllActivitiesForProjectQuery extends ActivitiesQueryBuilder
{
    public function __construct($projectId, ?Request $request = null)
    {
        $builder = Activity::where('project_id', $projectId);
        parent::__construct($builder, $request);
    }
}