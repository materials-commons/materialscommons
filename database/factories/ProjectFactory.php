<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Project::class, function (Faker $faker) {
    return [
        'name'            => "Project {$faker->randomNumber()}",
        'description'     => 'Project description',
        'summary'         => 'Project summary',
        'owner_id'        => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'uuid'            => $faker->uuid,
        'default_project' => false,
        'is_active'       => true,
        'file_types'      => [],
        'size'            => 0,
        'file_count'      => 0,
        'directory_count' => 0,
    ];
});
