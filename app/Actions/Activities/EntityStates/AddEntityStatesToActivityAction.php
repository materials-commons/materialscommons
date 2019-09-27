<?php

namespace App\Actions\Activities\EntityStates;

use App\Models\Activity;
use Illuminate\Support\Facades\DB;

class AddEntityStatesToActivityAction
{
    public function __invoke(Activity $activity, $entityStates)
    {
        $entries = collect();
        foreach ($entityStates as $entityState) {
            $entries->put($entityState["id"], ["direction" => $entityState["direction"]]);
        }

        $entities = DB::table('entity_states')->select('entity_id')->distinct()
                      ->whereIn('id', $entries->keys())
                      ->get()
                      ->map(
                          function($e) {
                              return $e->entity_id;
                          }
                      );

        DB::transaction(function() use ($activity, $entries, $entities) {
            $activity->entityStates()->attach($entries);

            $activity->entities()->attach($entities->map(function($e) {
                return $e->entity_id;
            }));
        });

        return $activity;
    }
}