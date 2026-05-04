<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use App\Traits\Projects\UserProjects;
use Illuminate\Http\Request;

class ShowMyResearchDashboardWebController extends Controller
{
    use UserProjects;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('app.dashboard.index', [
            'publishedDatasetsCount' => Dataset::whereNotNull('published_at')
                                               ->where('owner_id', auth()->id())
                                               ->count(),
            'deletedCount'           => Project::getDeletedTrashCountForUser(auth()->id()),
            'projectsCount'          => $this->getUserProjects(auth()->id())->count(),
            'archivedCount'          => $this->getUserArchivedProjectsCount(auth()->id()),
        ]);
    }
}
