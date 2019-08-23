<?php

namespace App\Http\Queries\Projects;

use App\Models\Project;
use Illuminate\Http\Request;

class SingleProjectForUserQuery extends ProjectsQueryBuilder
{
    public function __construct(?Request $request = null)
    {
        $projectId = $request->route('project');
        $query = Project::where('id', $projectId);
        parent::__construct($query, $request);
    }
}
