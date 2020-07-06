<?php

namespace App\Actions\Experiments;

use App\Models\Activity;
use App\Models\Entity;
use App\Models\EntityState;
use App\Models\Experiment;
use Illuminate\Support\Facades\DB;

class DeleteExperimentAction
{
    public function __invoke(Experiment $experiment)
    {
        DB::transaction(function () use ($experiment) {
            $experiment->entities()->get()->each(function (Entity $entity) {
                $entity->load('entityStates.attributes');
                $entity->entityStates->each(function (EntityState $es) {
                    $es->attributes()->delete();
                });
            });
            $experiment->entities()->delete();
            $experiment->activities()->get()->each(function (Activity $activity) {
                $activity->load('attributes');
                $activity->attributes()->delete();
            });
            $experiment->activities()->delete();
            $experiment->delete();
        });
    }
}