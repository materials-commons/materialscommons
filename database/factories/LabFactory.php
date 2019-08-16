<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Lab::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->sentence,
        'default_lab' => false,
        'owner_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
    ];
});
