<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Attribute;
use App\Models\EntityState;
use Faker\Generator as Faker;

$factory->define(Attribute::class, function (Faker $faker) {
    return [
        'name'              => $faker->sentence(6, true),
        'description'       => $faker->sentence,
        'uuid'              => $faker->uuid,
        'attributable_id'   => function () {
            return factory(EntityState::class)->create()->id;
        },
        'attributable_type' => EntityState::class,
    ];
});
