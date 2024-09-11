<?php

namespace App\Http\Controllers\Web\DataHQ\SamplesHQ;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;
use App\Traits\DataDictionaryQueries;
use App\ViewModels\DataDictionary\ShowProjectDataDictionaryViewModel;
use Illuminate\Http\Request;
use function session;
use function view;

class ShowActivityAttributeFiltersSampleHQWebController extends Controller
{
    use DataDictionaryQueries;

    public function __invoke(Request $request, Project $project)
    {
        $experiments = Experiment::where('project_id', $project->id)->get();
        $dataFor = session("{$project->id}:de:data-for");
        $deState = session($dataFor);
        $viewModel = (new ShowProjectDataDictionaryViewModel())
            ->withProject($project)
            ->withExperiments($experiments)
            ->withActivityAttributes($this->getUniqueActivityAttributesForProject($project->id))
            ->withEntityAttributes($this->getUniqueEntityAttributesForProject($project->id));
        return view('app.projects.datahq.sampleshq.index', $viewModel);
    }
}
