<?php

namespace App\Actions\Projects;

use App\Models\Project;
use Illuminate\Support\Carbon;

class DeleteProjectAction
{
    public function __invoke(Project $project)
    {
        $project->update([
            'deleted_at' => Carbon::now(),
            'name'       => "{$project->uuid}-{$project->name}"
        ]);
        $team = $project->team;
        $team->update(['name' => "{$team->uuid}-{$team->name}"]);
    }
}
