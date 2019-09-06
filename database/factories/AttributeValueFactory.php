<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Attribute;
use App\Models\AttributeValue;
use Faker\Generator as Faker;

$factory->define(AttributeValue::class, function (Faker $faker) {
    return [
        'attribute_id' => function () {
            return factory(Attribute::class)->create()->id;
        },
        'unit'         => 'm',
        'val'          => ['value' => 2],
    ];
});
