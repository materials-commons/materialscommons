<?php

namespace App\Http\Controllers\Web\Entities;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\Experiment;
use App\Models\Project;
use App\ViewModels\Entities\ShowEntityViewModel;
use Illuminate\Http\Request;

class ShowEntityWebController extends Controller
{
    public function __invoke(Request $request, Project $project)
    {
        $entityId = $request->route('entity');
        $entity = Entity::with('activities')->where('id', $entityId)->first();
        $showEntityViewModel = new ShowEntityViewModel($project, $entity);

        $experimentId = $request->route('experiment');
        if ($experimentId !== null) {
            $experiment = Experiment::find($experimentId);
            $showEntityViewModel->setExperiment($experiment);
            return view('app.projects.experiments.samples.show', $showEntityViewModel);
        }

        return view('app.projects.entities.show', $showEntityViewModel);
    }
}
