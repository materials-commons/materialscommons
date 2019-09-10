<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Project::class, function (Faker $faker) {
    return [
        'name'            => $faker->sentence(6, true),
        'description'     => $faker->sentence,
        'owner_id'        => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'uuid'            => $faker->uuid,
        'default_project' => false,
        'is_active'       => true,
    ];
});
