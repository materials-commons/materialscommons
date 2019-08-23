<?php

namespace App\Actions\Projects;

use App\Models\Project;

class UpdateProjectAction
{
    /**
     * @param $data
     * @param  Project  $project
     * @return mixed
     */
    public function __invoke($data, Project $project)
    {
        $project->update($data);
        return $project->fresh();
    }
}
