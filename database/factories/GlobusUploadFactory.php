<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\GlobusUploadDownload;
use App\Models\Project;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(GlobusUploadDownload::class, function (Faker $faker) {
    return [
        'uuid'       => $faker->uuid,
        'name'       => "GlobusUpload {$faker->randomNumber()}",
        'owner_id'   => function () {
            return factory(User::class)->create()->id;
        },
        'project_id' => function () {
            return factory(Project::class)->create()->id;
        },
        'loading'    => false,
        'uploading'  => true,
    ];
});
