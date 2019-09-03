<?php

namespace App\Actions\Activities\Files;

use App\Models\Activity;
use Illuminate\Support\Facades\DB;

class AddFileToActivityAction
{
    public function __invoke(Activity $activity, $fileId)
    {
        DB::transaction(function () use ($activity, $fileId) {
            $activity->files()->attach($fileId);
        });

        return $activity;
    }
}