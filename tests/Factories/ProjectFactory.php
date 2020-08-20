<?php

namespace Tests\Factories;

use App\Models\Experiment;
use App\Models\File;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use App\Traits\PathForFile;
use Illuminate\Support\Facades\Storage;

class ProjectFactory
{
    use PathForFile;

    protected $user;
    protected $createExperiment;

    public function ownedBy($user)
    {
        $this->user = $user;
        return $this;
    }

    public function withExperiment()
    {
        $this->createExperiment = true;
        return $this;
    }

    public function create()
    {
        $user = $this->user ?? factory(User::class)->create();
        $project = factory(Project::class)->create(['owner_id' => $user->id]);
        $team = Team::create([
            'name'     => "Team for {$project->name}",
            'owner_id' => $project->owner_id,
        ]);

        $project->update(['team_id' => $team->id]);
        $team->admins()->attach($project->owner);
        factory(File::class)->create([
            'project_id' => $project->id,
            'name'       => '/',
            'path'       => '/',
            'mime_type'  => 'directory',
            'owner_id'   => $user->id,
        ]);

        if ($this->createExperiment) {
            factory(Experiment::class)->create([
                'project_id' => $project->id,
            ]);
        }

        return $project;
    }

    public function addMemberToProject($user, $project)
    {
        $team = $project->team;
        $team->members()->syncWithoutDetaching($user);
    }

    public function createDirectory($project, $parent, $name)
    {
        $parentPath = $parent->path == '/' ? '' : $parent->path;

        return factory(File::class)->create([
            'project_id'   => $project->id,
            'name'         => $name,
            'path'         => "{$parentPath}/{$name}",
            'directory_id' => $parent->id,
            'owner_id'     => $project->owner_id,
            'mime_type'    => 'directory',
        ]);
    }

    public function createFile($project, $dir, $name, $content)
    {
        $file = factory(File::class)->create([
            'project_id'   => $project->id,
            'name'         => $name,
            'directory_id' => $dir->id,
            'owner_id'     => $project->owner_id,
            'mime_type'    => 'text',
        ]);

        $dirPath = Storage::disk('mcfs')->path($this->getDirPathForFile($file));
        if ( ! file_exists($dirPath)) {
            mkdir($dirPath, 0700, true);
        }

        $filePath = Storage::disk('mcfs')->path($this->getFilePathForFile($file));
        $handle = fopen($filePath, "w");
        fwrite($handle, $content);
        fclose($handle);

        return $file;
    }

    public function createFakeFile($project, $dir, $name)
    {
        return factory(File::class)->create([
            'project_id'   => $project->id,
            'name'         => $name,
            'directory_id' => $dir->id,
            'owner_id'     => $project->owner_id,
            'mime_type'    => 'text',
        ]);
    }

    public function createFilePointingAt($project, $file, $name)
    {
        return factory(File::class)->create([
            'project_id'   => $project->id,
            'name'         => $name,
            'directory_id' => $file->directory_id,
            'owner_id'     => $project->owner_id,
            'mime_type'    => 'text',
            'uses_uuid'    => $file->uuid,
        ]);
    }

    public function createExperimentInProject($project, $userId = null)
    {
        return factory(Experiment::class)->create([
            'project_id' => $project->id,
            'owner_id'   => $userId ?? $project->owner_id,
        ]);
    }
}
