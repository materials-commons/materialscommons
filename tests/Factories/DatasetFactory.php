<?php

namespace Tests\Factories;

use App\Models\Dataset;
use App\Models\File;
use App\Models\User;
use App\Traits\PathFromUUID;
use Facades\Tests\Factories\ProjectFactory;

class DatasetFactory
{
    use PathFromUUID;

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
        $parentPath = $parent->path == '/' ? '' : $parent->path;
        return factory(File::class)->create([
            'project_id'   => $dataset->project_id,
            'name'         => $name,
            'path'         => "{$parentPath}/{$name}",
            'directory_id' => $parent->id,
            'owner_id'     => $dataset->owner_id,
            'mime_type'    => 'directory',
        ]);
    }

    public function createFile($dataset, $dir, $name, $content)
    {
        $file = factory(File::class)->create([
            'project_id'   => $dataset->project_id,
            'name'         => $name,
            'directory_id' => $dir->id,
            'owner_id'     => $dataset->owner_id,
            'mime_type'    => 'text',
        ]);

        $dirPath = storage_path("app/".$this->getDirPathFromUuid($file->uuid));
        if (!file_exists($dirPath)) {
            mkdir($dirPath, 0700, true);
        }

        $filePath = storage_path("app/".$this->filePathFromUuid($file->uuid));
        $handle = fopen($filePath, "w");
        fwrite($handle, $content);
        fclose($handle);

        return $file;
    }

    public function createFilePointingAt($dataset, $file, $name)
    {
        return factory(File::class)->create([
            'project_id'   => $dataset->project_id,
            'name'         => $name,
            'directory_id' => $file->directory_id,
            'owner_id'     => $dataset->owner_id,
            'mime_type'    => 'text',
            'uses_uuid'    => $file->uuid,
        ]);
    }
}