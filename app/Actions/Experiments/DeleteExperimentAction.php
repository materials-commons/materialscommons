<?php

namespace App\Actions\Experiments;

use App\Models\Experiment;
use Illuminate\Support\Facades\DB;

class DeleteExperimentAction
{
    public function __invoke(Experiment $experiment)
    {
        DB::transaction(function () use ($experiment) {
            $experiment->entities()->delete();
            $experiment->activities()->delete();
            $experiment->delete();
        });
    }
}