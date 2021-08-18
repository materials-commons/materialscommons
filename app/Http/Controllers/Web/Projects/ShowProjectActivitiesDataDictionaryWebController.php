<?php

namespace App\Http\Controllers\Web\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Traits\DataDictionaryQueries;
use App\ViewModels\DataDictionary\ShowProjectDataDictionaryViewModel;
use Illuminate\Http\Request;

class ShowProjectActivitiesDataDictionaryWebController extends Controller
{
    use DataDictionaryQueries;

    public function __invoke(Request $request, Project $project)
    {
        $viewModel = (new ShowProjectDataDictionaryViewModel())
            ->withProject($project)
            ->withEntityAttributes($this->getUniqueEntityAttributesForProject($project->id))
            ->withActivityAttributes($this->getUniqueActivityAttributesForProject($project->id));
        return view('app.projects.show', $viewModel);
    }
}
