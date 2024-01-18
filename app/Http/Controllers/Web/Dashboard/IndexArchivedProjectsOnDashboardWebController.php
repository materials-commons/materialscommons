<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Traits\Projects\UserProjects;
use Illuminate\Http\Request;
use function auth;

class IndexArchivedProjectsOnDashboardWebController extends Controller
{
    use UserProjects;

    public function __invoke(Request $request)
    {
        $teamIds = $this->getUserTeamIds(auth()->id());
        $archivedProjects = $this->getUserArchivedProjectsFromTeamIds($teamIds);
        $projectsCount = $this->getUserProjectsCountFromTeamIds($teamIds);
        $publishedDatasetsCount = Dataset::whereNotNull('published_at')
                                         ->where('owner_id', auth()->id())
                                         ->count();
        return view('app.dashboard.index', [
            'projectsCount'          => $projectsCount,
            'publishedDatasetsCount' => $publishedDatasetsCount,
            'archivedCount'          => $archivedProjects->count(),
            'archivedProjects'       => $archivedProjects,
        ]);
    }
}
