<?php

namespace App\Actions\Projects;

use App\Models\Project;
use App\Traits\HasUniqueSlug;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RestoreProjectFromTrashAction
{
    use HasUniqueSlug;

    // Restores a project from the trash. This action requires the deleted_at
    // timestamp to be cleared. In addition, there are attributes on the project
    // there were changed so that the user can create other projects matching
    // the name of the deleted project. This makes restoration slightly complicated
    // as the user may have created another project with the same name. In that
    // case we need to append a number to the project name to make it unique.
    public function execute(Project$project)
    {
        $startingName = $project->name;
        if (Str::startsWith($project->name, "{$project->uuid}-")) {
            $startingName = Str::replaceStart("{$project->uuid}-", '', $project->name);
        }

        // Append this whenever the project name is not unique for this user.
        $count = 1;

        // Loop creating the unique name
        while(true) {
            DB::beginTransaction();
            try {
                $project->update(['name' => $startingName]);
                DB::commit();
                break;
            } catch (\Exception $e) {
                DB::rollBack();
                $startingName = "{$startingName}-{$count}";
                $count++;
            }
        }

        $project->refresh();

        // Now set the slug and the team name
        $this->addUniqueSlugToProject($project);
        $project->team->update(['name' => "Team for {$project->name}"]);

        // Restore the project by clearing the deleted_at timestamp
        $project->update(['deleted_at' => null]);

        return $project;
    }
}
