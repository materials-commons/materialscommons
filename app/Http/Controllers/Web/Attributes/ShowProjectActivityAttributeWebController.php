<?php

namespace App\Http\Controllers\Web\Attributes;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Traits\DataDictionaryQueries;
use App\ViewModels\Attributes\ShowExperimentAttributeViewModel;
use Illuminate\Http\Request;

class ShowProjectActivityAttributeWebController extends Controller
{
    use DataDictionaryQueries;

    public function __invoke(Request $request, Project $project)
    {
        $attributeName = $request->input('attribute', null);
        abort_if(is_null($attributeName), 400, "Attribute name is required");
        $viewModel = (new ShowExperimentAttributeViewModel())
            ->withProject($project)
            ->withAttributeValues($this->getActivityAttributeForProject($project->id, $attributeName));
        return view('app.projects.attributes.show', $viewModel);
    }
}
