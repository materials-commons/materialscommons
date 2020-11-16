<?php

namespace Database\Factories;

use App\Models\Attribute;
use App\Models\EntityState;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttributeFactory extends Factory
{
    protected $model = Attribute::class;

    public function definition()
    {
        return [
            'name'              => $this->faker->sentence(6, true),
            'description'       => "Attribute Description",
            'uuid'              => $this->faker->uuid,
            'attributable_id'   => function () {
                return EntityState::factory()->create()->id;
            },
            'attributable_type' => EntityState::class,
        ];
    }
}
