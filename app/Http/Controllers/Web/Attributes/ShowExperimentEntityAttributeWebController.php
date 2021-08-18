<?php

namespace App\Http\Controllers\Web\Attributes;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Project;
use App\Traits\DataDictionaryQueries;
use App\ViewModels\Attributes\ShowAttributeViewModel;
use Illuminate\Http\Request;

class ShowExperimentEntityAttributeWebController extends Controller
{
    use DataDictionaryQueries;

    public function __invoke(Request $request, Project $project, Experiment $experiment)
    {
        $attributeName = $request->input('attribute', null);
        abort_if(is_null($attributeName), 400, "Attribute name is required");
        $viewModel = (new ShowAttributeViewModel())
            ->withAttributeName($attributeName)
            ->withProject($project)
            ->withExperiment($experiment)
            ->withAttributeValues($this->getEntityAttributeForExperiment($experiment->id, $attributeName));
        return view('app.projects.experiments.attributes.show', $viewModel);
    }
}
