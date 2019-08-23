<?php

namespace App\Actions\Projects;

use App\Models\File;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class CreateProjectAction
{
    /**
     * @param $data
     * @return Project|null
     */
    public function __invoke($data)
    {
        $project = new Project($data);
        $project->owner_id = auth()->id();

        DB::transaction(function () use ($project) {
            $project->save();
            File::create([
                'project_id' => $project->id,
                'name' => '/',
                'path' => '/',
                'mime_type' => 'directory',
                'media_type_description' => 'directory',
                'owner_id' => auth()->id(),
            ]);
            auth()->user()->projects()->attach($project);
        });

        return $project->fresh();
    }
}
