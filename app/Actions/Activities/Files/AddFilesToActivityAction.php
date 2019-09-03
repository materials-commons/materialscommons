<?php

namespace App\Actions\Activities\Files;

use App\Models\Activity;
use Illuminate\Support\Facades\DB;

class AddFilesToActivityAction
{
    /**
     * Associate files with an activity
     *
     * @param  \App\Models\Activity  $activity
     * @param $files
     * @return \App\Models\Activity
     */
    public function __invoke(Activity $activity, $files)
    {
        DB::transaction(function () use ($activity, $files) {
            $activity->files()->attach($files);
        });

        return $activity;
    }
}
