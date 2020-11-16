<?php

namespace Database\Factories;

use App\Models\Community;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommunityFactory extends Factory
{
    protected $model = Community::class;

    public function definition()
    {
        return [
            'name'        => "Community {$this->faker->randomNumber()}",
            'description' => "Community description {$this->faker->randomNumber()}",
            'summary'     => "Community summary",
            'owner_id'    => function () {
                return User::factory()->create()->id;
            },
            'uuid'        => $this->faker->uuid,
            'public'      => true,
        ];
    }
}