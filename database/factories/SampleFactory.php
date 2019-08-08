<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Sample;
use Faker\Generator as Faker;

$factory->define(Sample::class, function (Faker $faker) {
    return [
        'name'        => $faker->word,
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
