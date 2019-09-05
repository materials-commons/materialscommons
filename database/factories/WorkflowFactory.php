<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use App\Models\Workflow;
use Faker\Generator as Faker;

$factory->define(Workflow::class, function (Faker $faker) {
    $workflow = <<<WORKFLOW
st=>start: Receive Samples
e=>end: Finished
sand_sample=>operation: Sand Sample
ht?=>condition: Heat Treat?
sem=>operation: SEM
ht_op=>operation: Heat Treatment 400c/2h
ht_op2=>operation: Heat Treatment 400c/2h
ht_op3=>operation: Heat Treatment 600c/1h
ht_op4=>operation: Heat Treatment 600c/4h
ht_op5=>operation: Heat Treatment 200c/3h

st->sand_sample(right)->ht?
ht?(yes, right)->ht_op(right)->ht_op2(right)->ht_op3(right)->ht_op4(right)->ht_op5
ht_op5->sem
ht?(no)->sem
sem->e
WORKFLOW;

    return [
        'name'        => $faker->name,
        'description' => $faker->sentence,
        'uuid'        => $faker->uuid,
        'owner_id'    => function () {
            return factory(User::class)->create()->id;
        },
        'workflow'    => $workflow,
    ];
});
