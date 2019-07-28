<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\File::class, function (Faker $faker) {
    $fileName = $faker->word . '.' . $faker->fileExtension;
    return [
        'name' => $fileName,
        'description' => $faker->sentence,
        'uuid' => $faker->uuid,
        'path' => 'P1/ab/02/' . $fileName,
        'checksum' => $faker->md5,
        'current' => true,
        'mime_type' => $faker->mimeType,
        'file_type' => $faker->numberBetween(0, 1),
    ];
});
