<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->sentence,
        'owner_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'uuid' => $faker->uuid,
        'default_project' => false,
        'is_active' => true,
    ];
});
