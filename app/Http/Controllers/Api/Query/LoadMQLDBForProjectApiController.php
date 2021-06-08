<?php

namespace App\Http\Controllers\Api\Query;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\Http;

class LoadMQLDBForProjectApiController extends Controller
{
    public function __invoke(Project $project)
    {
        return Http::Post("http://localhost:1324/api/load-project", [
            "project_id" => $project->id,
        ]);
    }
}
