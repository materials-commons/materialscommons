<?php

namespace Tests\Factories;

use App\Models\Dataset;
use App\Models\User;
use Facades\Tests\Factories\ProjectFactory;

class DatasetFactory
{
    protected $user;
    protected $project;

    public function ownedBy($user)
    {
        $this->user = $user;
        return $this;
    }

    public function inProject($project)
    {
        $this->project = $project;
        return $this;
    }

    public function create()
    {
        $user = $this->user ?? factory(User::class)->create();
        $project = $this->project ?? ProjectFactory::ownedBy($user)->create();
        return factory(Dataset::class)->create([
            'project_id' => $project->id,
            'owner_id'   => $user->id,
        ]);
    }

    public function createDirectory($dataset, $parent, $name)
    {
        return ProjectFactory::createDirectory($dataset->project, $parent, $name);
    }

    public function createFile($dataset, $dir, $name, $content)
    {
        return ProjectFactory::createFile($dataset->project, $dir, $name, $content);
    }

    public function createFilePointingAt($dataset, $file, $name)
    {
        return ProjectFactory::createFilePointingAt($dataset->project, $file, $name);
    }
}