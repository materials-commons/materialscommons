<?php

namespace App\Http\Controllers\Api\Query;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExecuteMQLApiController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        return Http::Post("http://localhost:1324/api/execute-query", [
            "project_id" => $project->id,
            "statement"  => $request->all(),
        ]);
    }
}
