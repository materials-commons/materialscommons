<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use App\Models\User;
use App\Traits\Projects\UserProjects;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use function auth;
use function is_null;
use function now;

class ShowDashboardProjectsWebController extends Controller
{
    use UserProjects;

    public function __invoke(Request $request)
    {
        $projects = $this->getUserProjects(auth()->id());
        $activeProjects = $this->getActiveProjects(auth()->user(), $projects);
        $recentlyAccessedProjects = $this->getRecentlyAccessedProjects(auth()->user(), $projects);
        return view('app.dashboard.index', [
            'activeProjects'           => $activeProjects,
            'recentlyAccessedProjects' => $recentlyAccessedProjects,
            'projects'                 => $projects,
            'projectsCount'            => $projects->count(),
            'deletedCount'             => Project::getDeletedTrashCountForUser(auth()->id()),
            'archivedCount'            => $this->getUserArchivedProjectsCount(auth()->id()),
            'publishedDatasetsCount'   => Dataset::whereNotNull('published_at')
                                                 ->where('owner_id', auth()->id())
                                                 ->count(),
        ]);
    }

    private function getActiveProjects(User $user, $projects)
    {
        if (!$user->hasActiveProjects()) {
            return collect();
        }

        return $projects->filter(function ($project) use ($user) {
            return $user->isActiveProject($project);
        })->sortByDesc(function ($project) use ($user) {
            $accessedOn = $user->projectRecentlyAccessedOn($project);
            if (is_null($accessedOn)) {
                return now()->getTimestamp();
            }
            return Carbon::parse($accessedOn)->getTimestamp();
        });
    }

    private function getRecentlyAccessedProjects(User $user, $projects)
    {
        if (!$user->hasRecentlyAccessedProjectsThatAreNotActive()) {
            return collect();
        }

        return $projects->filter(function ($project) use ($user) {
            if ($user->isActiveProject($project)) {
                return false;
            }

            if (is_null($user->projectRecentlyAccessedOn($project))) {
                return false;
            }

            return true;
        })->sortByDesc(function ($project) use ($user) {
            $accessedOn = $user->projectRecentlyAccessedOn($project);
            if (is_null($accessedOn)) {
                return now()->getTimestamp();
            }
            return Carbon::parse($accessedOn)->getTimestamp();
        });
    }
}
