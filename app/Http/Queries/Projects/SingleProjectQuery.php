<?php

namespace App\Http\Queries\Projects;

use App\Models\Project;
use App\Traits\GetRequestParameterId;
use Illuminate\Http\Request;

class SingleProjectQuery extends ProjectsQueryBuilder
{
    use GetRequestParameterId;

    public function __construct(?Request $request = null)
    {
        $projectId = $this->getParameterId('project');
        $query = Project::where('id', $projectId);
        parent::__construct($query, $request);
    }
}
