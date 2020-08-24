<?php

namespace App\Actions\Activities;

use App\Models\Activity;
use App\Models\Attribute;
use Illuminate\Support\Facades\DB;

class DeleteActivityAction
{
    /**
     * @param  Activity  $activity
     *
     * @throws \Exception
     */
    public function __invoke(Activity $activity)
    {
        DB::transaction(function () use ($activity) {
            $activity->attributes()->get()->each(function (Attribute $attribute) {
                $attribute->delete();
            });
            $activity->delete();
        });
    }
}
