<?php

namespace App\Http\Controllers\Web\DataHQ;

use App\Http\Controllers\Controller;
use App\Http\Requests\DataHQ\DataForRequest;
use App\Models\Project;
use Illuminate\Http\Request;
use function ray;
use function session;

class SaveDataForWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(DataForRequest $request, Project $project)
    {
        $validated = $request->validated();
//        session(["{$project->id}:de:data-for" => $validated["data_for"]]);
//        $dataFor = session("{$project->id}:de:data-for");
        return true;
    }
}
