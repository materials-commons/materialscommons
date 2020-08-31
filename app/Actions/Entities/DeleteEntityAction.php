<?php

namespace App\Actions\Entities;

use App\Models\Entity;
use App\Models\EntityState;
use Illuminate\Support\Facades\DB;

class DeleteEntityAction
{
    public function __invoke(Entity $entity)
    {
        DB::transaction(function () use ($entity) {
            $entity->entityStates()->get()->each(function (EntityState $es) {
                $es->attributes()->delete();
                $es->delete();
            });
            $entity->delete();
        });
    }
}
