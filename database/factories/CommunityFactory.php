<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Community;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Community::class, function(Faker $faker) {
    return [
        'name'        => "Community {$faker->randomNumber()}",
        'description' => "Community description {$faker->randomNumber()}",
        'owner_id'    => function() {
            return factory(User::class)->create()->id;
        },
        'uuid'        => $faker->uuid,
        'public'      => true,
    ];
});
