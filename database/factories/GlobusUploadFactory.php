<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\GlobusUpload;
use App\Models\Project;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(GlobusUpload::class, function (Faker $faker) {
    return [
        'uuid'               => $faker->uuid,
        'owner_id'           => function () {
            return factory(User::class)->create()->id;
        },
        'project_id'         => function () {
            return factory(Project::class)->create()->id;
        },
        'path'               => '/path',
        'globus_acl_id'      => 'acl_id',
        'globus_endpoint_id' => 'endpoint_id',
        'globus_identity_id' => 'identity_id',
        'loading'            => false,
    ];
});
