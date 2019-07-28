<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Directory;
use Faker\Generator as Faker;

$factory->define(Directory::class, function (Faker $faker) {
    return [
        'name' => '/',
        'description' => $faker->sentence,
        'uuid' => $faker->uuid,
        'project_id' => function () {
            return factory(App\Project::class)->create()->id;
        }
    ];
});
