<?php

namespace App\Actions\Experiments;

use App\Enums\ExperimentStatus;
use App\Models\Experiment;

class CreateExperimentAction
{
    public function __invoke($data)
    {
        $experiment           = new Experiment($data);
        $experiment->owner_id = auth()->id();
        $experiment->status   = ExperimentStatus::InProgress;
        $experiment->save();

        return $experiment->fresh();
    }
}