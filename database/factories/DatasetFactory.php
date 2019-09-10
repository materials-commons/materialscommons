<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Dataset;
use Faker\Generator as Faker;

$factory->define(Dataset::class, function (Faker $faker) {
    return [
        'name'         => $faker->sentence(6, true),
        'description'  => $faker->sentence,
        'owner_id'     => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'uuid'         => $faker->uuid,
        'funding'      => $faker->sentence,
        'project_id'   => function () {
            return factory(App\Models\Project::class)->create()->id;
        },
        'license'      => 'Public Domain Dedication and License (PDDL)',
        'license_link' => 'http://opendatacommons.org/licenses/pddl/summary/',
        'doi' => $faker->url,
        'institution' => $faker->word,
        'authors' => $faker->name,
        'published_at' => $faker->dateTime,
    ];
});
