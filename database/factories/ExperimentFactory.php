<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Experiment;
use Faker\Generator as Faker;

$factory->define(Experiment::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->sentence,
        'owner_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'uuid' => $faker->uuid,
        'project_id' => function () {
            return factory(App\Project::class)->create()->id;
        }
    ];
});
