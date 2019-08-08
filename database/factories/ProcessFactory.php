<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Process;
use Faker\Generator as Faker;

$factory->define(Process::class, function (Faker $faker) {
    return [
        'name'        => $faker->name,
        'description' => $faker->sentence,
        'uuid'        => $faker->uuid,
        'owner_id'    => function () {
            return factory(App\User::class)->create()->id;
        },
        'project_id'  => function () {
            return factory(App\Project::class)->create()->id;
        },
    ];
});
