<?php

namespace App\Http\Controllers\Web\Projects;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Traits\DataDictionaryQueries;
use App\ViewModels\DataDictionary\ShowProjectDataDictionaryViewModel;
use App\ViewModels\Projects\ShowProjectViewModel;
use Illuminate\Support\Facades\DB;

class ShowProjectWebController extends Controller
{
    use DataDictionaryQueries;

    public function __invoke($projectId)
    {
        $project = Project::with(['owner', 'team.members', 'team.admins', 'rootDir'])
                          ->withCount('experiments', 'entities', 'publishedDatasets', 'unpublishedDatasets')
                          ->where('id', $projectId)
                          ->first();
        $readme = File::where('name', "readme.md")
                      ->where("project_id", $projectId)
                      ->where("directory_id", $project->rootDir->id)
                      ->where('current', true)
                      ->whereNull('dataset_id')
                      ->whereNull('deleted_at')
                      ->first();
        $viewModel = (new ShowProjectViewModel($project))
            ->withReadme($readme)
            ->withActivityAttributesCount($this->getUniqueActivityAttributesForProject($projectId)->count())
            ->withEntityAttributesCount($this->getUniqueEntityAttributesForProject($projectId)->count());
        return view('app.projects.show', $viewModel);
    }
}
