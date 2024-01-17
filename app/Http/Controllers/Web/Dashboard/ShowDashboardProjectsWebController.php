<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Traits\Projects\UserProjects;
use Illuminate\Http\Request;
use function auth;

class ShowDashboardProjectsWebController extends Controller
{
    use UserProjects;

    public function __invoke(Request $request)
    {
        $projects = $this->getUserProjects(auth()->id());
        return view('app.dashboard.index', [
            'projects'               => $projects,
            'projectsCount'          => $projects->count(),
            'archivedCount' => $this->getUserArchivedProjectsCount(auth()->id()),
            'publishedDatasetsCount' => Dataset::whereNotNull('published_at')
                                               ->where('owner_id', auth()->id())
                                               ->count(),
        ]);
    }
}
