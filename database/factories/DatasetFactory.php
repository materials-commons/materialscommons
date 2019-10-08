<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Dataset;
use Faker\Generator as Faker;

$factory->define(Dataset::class, function(Faker $faker) {
    return [
        'name'           => "Dataset {$faker->randomNumber()}",
        'description'    => "Dataset description",
        'owner_id'       => function() {
            return factory(App\Models\User::class)->create()->id;
        },
        'uuid'           => $faker->uuid,
        'funding'        => "Dataset funding",
        'project_id'     => function() {
            return factory(App\Models\Project::class)->create()->id;
        },
        'license'        => 'Public Domain Dedication and License (PDDL)',
        'license_link'   => 'http://opendatacommons.org/licenses/pddl/summary/',
        'doi'            => $faker->url,
        'institution'    => "Dataset institution",
        'authors'        => $faker->name,
        'published_at'   => $faker->dateTime,
        'file_selection' => [
            'include_files' => [],
            'exclude_files' => [],
            'include_dirs'  => [],
            'exclude_dirs'  => [],
        ],
    ];
});
