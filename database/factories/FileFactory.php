<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\File::class, function (Faker $faker) {
    $fileName = $faker->word.'.'.$faker->fileExtension;

    return [
        'name'        => $fileName,
        'description' => $faker->sentence,
        'summary'     => 'File summary',
        'uuid'        => $faker->uuid,
        'checksum'    => $faker->md5,
        'mime_type'   => $faker->mimeType,
        'owner_id'    => function () {
            return factory(App\Models\User::class)->create()->id;
        },
        'project_id'  => function () {
            return factory(App\Models\Project::class)->create()->id;
        },
        'size'        => $faker->randomNumber(),
    ];
});
