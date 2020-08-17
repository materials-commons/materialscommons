<?php

namespace App\Actions\Projects;

use App\Models\Project;
use Illuminate\Support\Facades\DB;

class DeleteProjectAction
{
    /**
     * Delete a project and all its dependencies.
     *
     * @param  \App\Models\Project  $project
     *
     * @throws \Exception
     */
    public function __invoke(Project $project)
    {
        DB::transaction(function () use ($project) {
            $team = $project->team;
            $project->delete();
            $team->delete();
        });
    }
}
