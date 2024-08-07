<?php

namespace App\Http\Controllers\Web\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Traits\DataDictionaryQueries;
use App\ViewModels\DataDictionary\ShowProjectDataDictionaryViewModel;
use Illuminate\Http\Request;

class ShowProjectEntitiesDataDictionaryWebController extends Controller
{
    use DataDictionaryQueries;

    public function __invoke(Request $request, Project $project)
    {
        $viewModel = (new ShowProjectDataDictionaryViewModel())
            ->withProject($project)
            ->withActivityAttributes($this->getUniqueActivityAttributesForProject($project->id))
            ->withEntityAttributes($this->getUniqueEntityAttributesForProject($project->id));
        return view('app.projects.show', $viewModel);
    }
}
