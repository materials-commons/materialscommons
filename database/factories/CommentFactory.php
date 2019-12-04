<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Comment::class, function (Faker $faker) {
    return [
        'body'     => 'comment body',
        'title'    => 'comment title',
        'uuid'     => $faker->uuid,
        'owner_id' => function () {
            return factory(App\Models\User::class)->create()->id;
        },
    ];
});
