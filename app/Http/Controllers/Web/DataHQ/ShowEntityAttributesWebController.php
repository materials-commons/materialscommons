<?php

namespace App\Http\Controllers\Web\DataHQ;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;
use App\Traits\DataDictionaryQueries;
use App\ViewModels\DataDictionary\ShowProjectDataDictionaryViewModel;
use Illuminate\Http\Request;
use function view;

class ShowEntityAttributesWebController extends Controller
{
    use DataDictionaryQueries;

    public function __invoke(Request $request, Project $project)
    {
        $experiments = Experiment::where('project_id', $project->id)->get();
        $viewModel = (new ShowProjectDataDictionaryViewModel())
            ->withProject($project)
            ->withExperiments($experiments)
            ->withActivityAttributes($this->getUniqueActivityAttributesForProject($project->id))
            ->withEntityAttributes($this->getUniqueEntityAttributesForProject($project->id));
        return view('app.projects.datahq.index', $viewModel);
    }
}
