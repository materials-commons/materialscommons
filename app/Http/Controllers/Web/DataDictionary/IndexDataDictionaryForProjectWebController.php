<?php

namespace App\Http\Controllers\Web\DataDictionary;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Traits\DataDictionaryQueries;
use App\ViewModels\DataDictionary\ShowProjectDataDictionaryViewModel;

class IndexDataDictionaryForProjectWebController extends Controller
{
    use DataDictionaryQueries;
    public function __invoke(Project $project)
    {
        $viewModel = (new ShowProjectDataDictionaryViewModel())
            ->withProject($project)
            ->withActivityAttributes($this->getUniqueActivityAttributesForProject($project->id))
            ->withEntityAttributes($this->getUniqueEntityAttributesForProject($project->id));
        return view('app.projects.data-dictionary.index', $viewModel);
    }
}
