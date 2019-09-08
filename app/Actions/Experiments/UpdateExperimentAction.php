<?php

namespace App\Actions\Experiments;

use App\Models\Experiment;

class UpdateExperimentAction
{
    public function __invoke($data, Experiment $experiment)
    {
        return tap($experiment)->update($data)->fresh();
    }
}