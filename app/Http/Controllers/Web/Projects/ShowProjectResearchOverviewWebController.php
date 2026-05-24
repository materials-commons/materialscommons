<?php

namespace App\Http\Controllers\Web\Projects;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Traits\DataDictionaryQueries;
use App\ViewModels\DataDictionary\ShowProjectDataDictionaryViewModel;
use App\ViewModels\Projects\ShowProjectViewModel;
use Illuminate\Http\Request;

class ShowProjectResearchOverviewWebController extends Controller
{
    use DataDictionaryQueries;
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $projectId)
    {
        $project = Project::with(['owner', 'team.members', 'team.admins', 'rootDir', 'experiments'])
                          ->withCount('experiments', 'entities', 'publishedDatasets', 'unpublishedDatasets')
                          ->where('id', $projectId)
                          ->first();
        $user = auth()->user();

        $user->addToRecentlyAccessedProjects($project);

        $readme = File::where('name', "readme.md")
                      ->where("project_id", $projectId)
                      ->where("directory_id", $project->rootDir->id)
                      ->active()
                      ->first();
        $viewModel =  (new ShowProjectViewModel($project))
            ->withReadme($readme)
            ->withActivityAttributesCount($this->getUniqueActivityAttributesForProject($project->id)->count())
            ->withEntityAttributesCount($this->getUniqueEntityAttributesForProject($project->id)->count());
        return view('app.projects.show', $viewModel);
    }
}
