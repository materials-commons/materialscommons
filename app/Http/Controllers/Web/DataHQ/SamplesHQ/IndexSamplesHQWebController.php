<?php

namespace App\Http\Controllers\Web\DataHQ\SamplesHQ;

use App\DTO\DataExplorerState;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Traits\Entities\EntityAndAttributeQueries;
use Illuminate\Http\Request;
use function session;
use function view;

class IndexSamplesHQWebController extends Controller
{
    use EntityAndAttributeQueries;

    public function __invoke(Request $request, Project $project)
    {
        $deState = session("p:{$project->id}:de:state", new DataExplorerState());
        $deState->dataFor = "p:{$project->id}:de:state";
        session(["p:{$project->id}:de:state" => $deState]);
        session(["{$project->id}:de:data-for" => $deState->dataFor]);
        return view('app.projects.datahq.sampleshq.index', [
            'project'           => $project,
            'deState'           => $deState,
        ]);
    }
}
