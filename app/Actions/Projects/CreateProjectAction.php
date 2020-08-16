<?php

namespace App\Actions\Projects;

use App\Models\File;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

class CreateProjectAction
{
    public function execute($data, $ownerId)
    {
        $project = Project::with('rootDir')
                          ->where('name', $data['name'])
                          ->where('owner_id', $ownerId)
                          ->first();

        if ($project !== null) {
            return ['project' => $project, 'created' => false];
        }

        $project = new Project($data);
        $project->owner_id = $ownerId;

        DB::transaction(function () use ($project, $ownerId) {
            $project->save();
            File::create([
                'project_id'             => $project->id,
                'name'                   => '/',
                'path'                   => '/',
                'mime_type'              => 'directory',
                'media_type_description' => 'directory',
                'owner_id'               => $ownerId,
            ]);
            $team = Team::create([
                'name'     => "Team for {$project->name}",
                'owner_id' => $project->owner_id,
            ]);

            $project->update(['team_id' => $team->id]);
            $team->admins()->attach($project->owner);
        });

        $project->fresh();
        return ['project' => Project::with('rootDir')->find($project->id), 'created' => true];
    }
}
