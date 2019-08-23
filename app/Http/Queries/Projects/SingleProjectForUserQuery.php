<?php

namespace App\Http\Queries\Projects;

use Illuminate\Http\Request;
use App\Models\Project;

class SingleProjectForUserQuery extends ProjectsQueryBuilder
{
    public function __construct(?Request $request = null)
    {
        $projectId = $request->route('project');
        $query = Project::findOrFail($projectId)->query();
        parent::__construct($query, $request);
    }
}
