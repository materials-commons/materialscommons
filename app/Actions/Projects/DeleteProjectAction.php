<?php

namespace App\Actions\Projects;

use App\Models\Project;
use Illuminate\Support\Carbon;

class DeleteProjectAction
{
    public function __invoke(Project $project)
    {
        $project->update(['deleted_at' => Carbon::now()]);
//        DB::transaction(function () use ($project) {
//            $team = $project->team;
//            $project->files()->delete();
//            $project->delete();
//            $team->delete();
//        });
    }
}
