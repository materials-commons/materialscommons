<?php

namespace App\Actions\Projects;

use App\Models\Project;
use App\Traits\HasUniqueSlug;

class UpdateProjectAction
{
    use HasUniqueSlug;

    /**
     * @param $data
     * @param  Project  $project
     * @return mixed
     */
    public function __invoke($data, Project $project)
    {
        $nameUpdated = false;
        if ($project->name !== $data['name']) {
            $nameUpdated = true;
        }
        $updatedProject = tap($project)->update($data)->fresh();

        if ($nameUpdated) {
            // The user changed the name of the project. So we need to update the name
            // of the team associated with the project and the slug associated with
            // the project. The following is not very efficient as we are doing multiple
            // calls to the database for what could be a single update above. However,
            // creating a unique slug involves retrying multiple times to make sure there
            // isn't a collision. Changing the team name is a separate table, which makes
            // this easier to code this way. Given that this bit of code isn't called
            // very often, the impact of the calls will be very small.

            // Update the slug based on the new name.
            $this->addUniqueSlugToProject($updatedProject);

            // Update the team name.
            $updatedProject->team->update(['name' => "Team for {$updatedProject->name}"]);
            $updatedProject->fresh();
        }

        return $updatedProject;
    }
}
