<?php

namespace App\Actions\Projects;

use App\Models\File;
use App\Models\Project;
use App\Models\Team;
use App\Traits\HasUniqueSlug;
use Illuminate\Support\Facades\DB;

class CreateProjectAction
{
    use HasUniqueSlug;

    public function execute($data, $ownerId)
    {
        $project = Project::with('rootDir')
                          ->where('name', $data['name'])
                          ->where('owner_id', $ownerId)
            ->whereNull('deleted_at')
                          ->first();

        if ($project !== null) {
            return ['project' => $project, 'created' => false];
        }

        $data['file_types'] = [];
        $data['file_count'] = 0;
        $data['directory_count'] = 0;
        $data['size'] = 0;
        $data['disk'] = 'mcfs';
        $project = new Project($data);
        $project->owner_id = $ownerId;

        try {

            DB::transaction(function () use ($project, $ownerId) {
                $project->save();
                File::create([
                    'project_id'             => $project->id,
                    'name'                   => '/',
                    'path'                   => '/',
                    'mime_type'              => 'directory',
                    'media_type_description' => 'directory',
                    'current'                => true,
                    'owner_id'               => $ownerId,
                ]);
                $team = Team::create([
                    'name'     => "Team for {$project->name}",
                    'owner_id' => $project->owner_id,
                ]);

                $project->update(['team_id' => $team->id]);
                $team->admins()->attach($project->owner);
            });
        } catch (\Exception $e) {
            return ['project' => null, 'created' => false];
        }

        $this->addUniqueSlugToProject($project);

        $project->refresh();
        return [
            'project' => Project::with(['rootDir', 'team.members', 'team.admins'])->find($project->id),
            'created' => true,
        ];
    }
}
