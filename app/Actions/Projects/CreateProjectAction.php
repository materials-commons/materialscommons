<?php

namespace App\Actions\Projects;

use App\Models\File;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class CreateProjectAction
{
    public function execute($data, $ownerId)
    {
        $project = Project::where('name', $data['name'])->where('owner_id', $ownerId)->first();
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
            auth()->user()->projects()->attach($project);
        });

        return ['project' => $project->fresh(), 'created' => true];
    }
}
