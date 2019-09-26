<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Experiment;
use Faker\Generator as Faker;

$factory->define(Experiment::class, function (Faker $faker) {
    return [
        'name'        => "Experiment {$faker->randomNumber()}",
        'description' => "Experiment description",
        'loading'     => false,
        'owner_id'    => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'uuid'        => $faker->uuid,
        'project_id'  => function () {
            return factory(App\Models\Project::class)->create()->id;
        },
    ];
});
