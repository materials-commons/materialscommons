<?php

namespace Tests\Factories;

use App\Models\File;
use App\Models\Project;
use App\Models\User;

class ProjectFactory
{
    protected $user;

    public function ownedBy($user)
    {
        $this->user = $user;
        return $this;
    }

    public function create()
    {
        $user = $this->user ?? factory(User::class)->create();
        $project = factory(Project::class)->create(['owner_id' => $user->id]);
        $user->projects()->attach($project);
        factory(File::class)->create([
            'project_id' => $project->id,
            'name'       => '/',
            'path'       => '/',
            'mime_type'  => 'directory',
            'owner_id'   => $user->id,
        ]);

        return $project;
    }
}