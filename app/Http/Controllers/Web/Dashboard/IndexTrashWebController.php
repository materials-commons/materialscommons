<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use App\Traits\Projects\UserProjects;
use Illuminate\Http\Request;
use function auth;
use function view;

class IndexTrashWebController extends Controller
{
    use UserProjects;

    public function __invoke(Request $request)
    {
        $teamIds = $this->getUserTeamIds(auth()->id());
        $archivedCount = $this->getUserArchivedProjectsCountFromTeamIds($teamIds);
        $projectsCount = $this->getUserProjectsCountFromTeamIds($teamIds);
        $publishedDatasetsCount = Dataset::whereNotNull('published_at')
                                         ->where('owner_id', auth()->id())
                                         ->count();
        $deletedProjects = Project::getDeletedForUser(auth()->id());
        $deletedCount = $deletedProjects->count();
        $expiresInDays = config('trash.expires_in_days');
        $now = now();

        return view('app.dashboard.index', [
            'projectsCount'          => $projectsCount,
            'publishedDatasetsCount' => $publishedDatasetsCount,
            'archivedCount'          => $archivedCount,
            'deletedProjects'        => $deletedProjects,
            'deletedCount'           => $deletedCount,
            'expiresInDays'          => $expiresInDays,
            'now'                    => $now,
        ]);
    }
}
