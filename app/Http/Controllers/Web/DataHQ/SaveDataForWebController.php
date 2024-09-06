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
        ray("setting session {$project->id}:de:data-for to {$validated['data_for']}");
        session(["{$project->id}:de:data-for" => $validated["data_for"]]);
        $dataFor = session("{$project->id}:de:data-for");
        ray("after setting checking value {$dataFor}");
        ray($request->session()->all());
        return true;
    }
}
