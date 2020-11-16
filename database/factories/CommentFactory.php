<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'body'     => 'comment body',
            'title'    => 'comment title',
            'uuid'     => $this->faker->uuid,
            'owner_id' => function () {
                return User::factory()->create()->id;
            },
        ];
    }
}
