<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Entity;
use Faker\Generator as Faker;

$factory->define(Entity::class, function (Faker $faker) {
    return [
        'name'        => $faker->sentence(6, true),
        'description' => $faker->sentence,
        'uuid'        => $faker->uuid,
        'owner_id'    => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'project_id'  => function () {
            return factory(App\Models\Project::class)->create()->id;
        },
    ];
});
