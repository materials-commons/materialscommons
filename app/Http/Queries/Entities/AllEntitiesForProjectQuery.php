<?php

namespace App\Http\Queries\Entities;

use App\Models\Entity;
use Illuminate\Http\Request;

class AllEntitiesForProjectQuery extends EntitiesQueryBuilder
{
    public function __construct($projectId, ?Request $request = null)
    {
        $builder = Entity::where('project_id', $projectId);
        parent::__construct($builder, $request);
    }
}