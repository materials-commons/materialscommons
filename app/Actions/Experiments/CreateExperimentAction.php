<?php

namespace App\Actions\Experiments;

class CreateExperimentAction
{
    public function __invoke($data)
    {
        $experiment = new Experiment($data);
        $experiment->owner_id = auth()->id();
        $experiment->save();

        return $experiment->fresh();
    }
}