<?php

namespace App\Http\Middleware;

use App\Models\Activity;
use App\Models\Dataset;
use App\Models\Entity;
use App\Models\Experiment;
use App\Models\File;
use App\Models\Project;
use App\Traits\GetRequestParameterId;
use App\Traits\Projects\CanAccessProject;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use function auth;

class UserCanAccessProject
{
    use CanAccessProject;
    use GetRequestParameterId;

    /**
     * Make sure user has access to the project. $next is called if no project route, or project_id parameter
     * is found.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $projectId = $this->resolveProjectIdFromRequest();
        if (blank($projectId)) {
            return $next($request);
        }

        if ($this->isPublicPath()) {
            abort_unless($this->isPublicProject($projectId), 404, 'No such project');
            return $next($request);
        }

        if (optional(auth()->user())->is_admin) {
            return $next($request);
        }

        abort_unless($this->canAccessPrivateProject($projectId), 404, 'No such project');

        return $next($request);
    }

    private function resolveProjectIdFromRequest()
    {
        $projectId = $this->getParameterId('project');
        if (!blank($projectId)) {
            return $projectId;
        }

        $projectId = request()->input('project_id');
        if (!blank($projectId)) {
            return $projectId;
        }

        $projectId = request()->input('project');
        if (!blank($projectId)) {
            return $projectId;
        }

        foreach ($this->projectOwnedResourceParameters() as $parameterName => $modelClass) {
            $projectId = $this->projectIdFromResourceParameter($parameterName, $modelClass);

            if (!blank($projectId)) {
                return $projectId;
            }
        }

        return null;
    }

    private function projectIdFromResourceParameter(string $parameterName, string $modelClass)
    {
        $resourceId = $this->getParameterId($parameterName);

        if (blank($resourceId)) {
            return null;
        }

        return $modelClass::where('id', $resourceId)->value('project_id');
    }

    private function projectOwnedResourceParameters(): array
    {
        return [
            'file'       => File::class,
            'directory'  => File::class,
            'folder'     => File::class,
            'dataset'    => Dataset::class,
            'experiment' => Experiment::class,
            'entity'     => Entity::class,
            'activity'   => Activity::class,
        ];
    }

    private function isPublicPath(): bool
    {
        return Str::contains(request()->url(), "/public");
    }

    private function isPublicProject($projectId): bool
    {
        $count = Project::where('id', $projectId)->where('is_public', true)->count();
        return $count == 1;
    }

    private function canAccessPrivateProject($projectId): bool
    {
        $project = Project::find($projectId);

        if (is_null($project)) {
            return false;
        }

        return $this->userCanAccessProject(auth()->id(), $project);
    }
}
