<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Action;
use Faker\Generator as Faker;

$factory->define(Action::class, function (Faker $faker) {
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
