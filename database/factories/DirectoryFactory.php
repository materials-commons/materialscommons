<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Directory;
use Faker\Generator as Faker;

$factory->define(Directory::class, function (Faker $faker) {
    return [
        'name'        => '/',
        'description' => $faker->sentence,
        'summary'     => 'File summary',
        'uuid'        => $faker->uuid,
        'project_id'  => function () {
            return factory(App\Models\Project::class)->create()->id;
        },
    ];
});
