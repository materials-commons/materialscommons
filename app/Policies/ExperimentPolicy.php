<?php

namespace App\Policies;

use App\Models\Experiment;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExperimentPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function canDeleteExperiment(User $user, Project $project, Experiment $experiment)
    {
        if ($experiment->owner_id == $user->id) {
            return true;
        }

        if ($project->owner_id == $user->id) {
            return true;
        }

        return false;
    }
}
