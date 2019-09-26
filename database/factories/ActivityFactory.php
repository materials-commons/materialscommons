<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Activity;
use Faker\Generator as Faker;

$factory->define(Activity::class, function (Faker $faker) {
    return [
        'name'        => "Process {$faker->randonNumber()}",
        'description' => "Process Description",
        'uuid'        => $faker->uuid,
        'owner_id'    => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'project_id'  => function () {
            return factory(App\Models\Project::class)->create()->id;
        },
    ];
});
