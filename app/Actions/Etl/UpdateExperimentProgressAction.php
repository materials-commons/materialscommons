<?php

namespace App\Actions\Etl;

use App\Models\Experiment;

class UpdateExperimentProgressAction
{
    public function __invoke($experimentId, $status)
    {
        return tap(Experiment::findOrFail($experimentId))->update(['loading' => $status])->fresh();
    }
}