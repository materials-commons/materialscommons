<?php

namespace App\Actions\Experiments;

use App\Models\Experiment;

class DeleteExperimentAction
{
    public function __invoke(Experiment $experiment)
    {
        return $experiment->delete();
    }
}