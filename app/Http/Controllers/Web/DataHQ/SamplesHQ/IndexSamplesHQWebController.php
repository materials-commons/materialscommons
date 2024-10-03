<?php

namespace App\Http\Controllers\Web\DataHQ\SamplesHQ;

use App\DTO\DataHQ\DataHQState;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\DataHQ\DataHQContextStateStoreInterface;
use App\Services\DataHQ\DataHQStateStore;
use App\Traits\Entities\EntityAndAttributeQueries;
use Illuminate\Http\Request;
use function view;

class IndexSamplesHQWebController extends Controller
{
    use EntityAndAttributeQueries;

    public function __invoke(Request $request, Project $project)
    {
        DataHQStateStore::saveState(new DataHQState(DataHQContextStateStoreInterface::SAMPLESHQ_STATE_KEY, 'project',
            $project->id));
        return view('app.projects.datahq.sampleshq.index', [
            'project' => $project,
        ]);
    }
}
