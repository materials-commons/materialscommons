<?php

namespace Tests\Factories;

use App\Models\File;
use App\Models\Project;
use App\Models\User;
use App\Traits\PathForFile;

class ProjectFactory
{
    use PathForFile;

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

        $dirPath = storage_path("app/".$this->getDirPathForFile($file));
        if ( ! file_exists($dirPath)) {
            mkdir($dirPath, 0700, true);
        }

        $filePath = storage_path("app/".$this->getFilePathForFile($file));
        $handle = fopen($filePath, "w");
        fwrite($handle, $content);
        fclose($handle);

        return $file;
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
}