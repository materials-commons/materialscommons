<?php

use Faker\Generator as Faker;

$factory->define(App\Lab::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->sentence,
        'default_lab' => false,
        'owner_id' => function () {
            return factory(App\User::class)->create()->id;
        },
    ];
});
