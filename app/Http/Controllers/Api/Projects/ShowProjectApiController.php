<?php

namespace App\Http\Controllers\Api\Projects;

use App\Http\Resources\Projects\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ShowProjectApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  Request  $request
     * @param $projectId
     * @return ProjectResource
     */
    public function __invoke(Request $request, $projectId)
    {
        $query = Project::findOrFail($projectId)->query();
        $data = QueryBuilder::for($query)
            ->allowedFields(['name', 'id', 'uuid', 'description'])
            ->withCount(['activities', 'entities', 'files'])->get();
        return new ProjectResource($data[0]);
    }
}
