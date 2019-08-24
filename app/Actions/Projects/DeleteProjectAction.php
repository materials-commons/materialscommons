<?php

namespace App\Actions\Projects;

use App\Models\Project;

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
        $project->delete();
    }
}
