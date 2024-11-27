<?php

namespace App\Http\Controllers\Web\DataHQ\SamplesHQ;

use App\DTO\DataHQ\DataHQState;
use App\Http\Controllers\Controller;
use App\Models\DatahqTab;
use App\Models\Project;
use App\Models\User;
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
        $user = auth()->user();
//        $tabs = DatahqTab::with('datahqCharts')
//                         ->where('project_id', $project->id)
//                         ->where('user_id', $user->id)
//                         ->get();
//        if ($tabs->count() === 0) {
//            $tabs = $this->setupSamplesHQ($project, $user);
//        }

        DataHQStateStore::saveState(new DataHQState(DataHQContextStateStoreInterface::SAMPLESHQ_STATE_KEY, 'project',
            $project->id));
        return view('app.projects.datahq.sampleshq.index', [
            'project' => $project,
        ]);
    }

    private function setupSamplesHQ(Project $project, User $user)
    {
        $tab = DatahqTab::create([
            'project_id' => $project->id,
            'user_id'    => $user->id,
            'name'       => 'All Samples',
        ]);


        return collect();
    }
}
