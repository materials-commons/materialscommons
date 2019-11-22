<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use App\Models\Workflow;
use Faker\Generator as Faker;

$factory->define(Workflow::class, function (Faker $faker) {
    $workflow = <<<WORKFLOW
Sand Sample(right)->Heat Treat?(yes, right)->HT 400c/2h(right)->HT 400c/2h(right)->HT 600c/1h(right)->HT 200c/3h(right)->sem
Heat Treat?(no)->sem
WORKFLOW;

    return [
        'name'        => $faker->sentence(6, true),
        'description' => $faker->sentence,
        'uuid'        => $faker->uuid,
        'owner_id'    => function () {
            return factory(User::class)->create()->id;
        },
        'workflow'    => $workflow,
    ];
});
