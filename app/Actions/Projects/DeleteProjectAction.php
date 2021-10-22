<?php

namespace App\Actions\Projects;

use App\Models\Project;
use Illuminate\Support\Facades\DB;

class DeleteProjectAction
{
    public function __invoke(Project $project)
    {
        DB::transaction(function () use ($project) {
            $team = $project->team;
            $project->files()->delete();
            $project->delete();
            $team->delete();
        });
    }
}
