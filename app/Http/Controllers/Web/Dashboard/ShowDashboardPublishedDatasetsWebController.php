<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use App\Traits\Projects\UserProjects;
use Illuminate\Http\Request;
use function auth;

class ShowDashboardPublishedDatasetsWebController extends Controller
{
    use UserProjects;

    public function __invoke(Request $request)
    {
        $publishedDatasets = $this->getPublishedDatasets();
        return view('app.dashboard.index', [
            'publishedDatasets'      => $publishedDatasets,
            'publishedDatasetsCount' => $publishedDatasets->count(),
            'deletedCount' => Project::getDeletedTrashCountForUser(auth()->id()),
            'projectsCount'          => $this->getUserProjects(auth()->id())->count(),
            'archivedCount' => $this->getUserArchivedProjectsCount(auth()->id()),
        ]);
    }

    private function getPublishedDatasets()
    {
        return Dataset::withCount('views', 'downloads', 'comments')
                      ->where('owner_id', auth()->id())
                      ->whereNotNull('published_at')
                      ->get();
    }
}
