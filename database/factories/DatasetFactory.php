<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Dataset;
use Faker\Generator as Faker;

$factory->define(Dataset::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->sentence,
        'owner_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'uuid' => $faker->uuid,
        'funding' => $faker->sentence,
        'project_id' => function () {
            return factory(App\Project::class)->create()->id;
        },
        'institution' => $faker->word,
        'authors' => $faker->name,
        'published_on' => $faker->dateTime,
    ];
});
