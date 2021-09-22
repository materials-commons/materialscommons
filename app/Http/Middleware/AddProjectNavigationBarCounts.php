<?php

namespace App\Http\Middleware;

use App\Models\File;
use App\Traits\GetRequestParameterId;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class AddProjectNavigationBarCounts
{
    use GetRequestParameterId;

    public function handle(Request $request, Closure $next)
    {
        $projectId = $this->getParameterId('project');
        if ($projectId == '') {
            return $next($request);
        }

        View::share('nav_trash_count', $this->getTrashCounts($projectId));

//        $project = Project::with(['team.members', 'team.admins'])
//                          ->withCount('experiments', 'entities', 'publishedDatasets', 'unpublishedDatasets',
//                              'workflows')
//                          ->findOrFail($projectId);
//        View::share('nav_experiments_count', $project->experiments_count);
//        View::share('nav_entities_count', $project->entities_count);
//        View::share('nav_pub_ds_count', $project->published_datasets_count);
//        View::share('nav_unpub_ds_count', $project->unpublished_datasets_count);
//        View::share('nav_workflows_count', $project->workflows_count);
//        View::share('nav_members_count', $project->team->members->count());
//        View::share('nav_admins_count', $project->team->admins->count());

        return $next($request);
    }

    private function getTrashCounts($projectId): int
    {
        return File::where('project_id', $projectId)
                   ->where('mime_type', 'directory')
                   ->where('current', false)
                   ->count();
    }
}
