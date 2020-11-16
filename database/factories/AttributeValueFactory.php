<?php

namespace Database\Factories;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttributeValueFactory extends Factory
{
    protected $model = AttributeValue::class;

    public function definition()
    {
        return [
            'attribute_id' => function () {
                return Attribute::factory()->create()->id;
            },
            'unit'         => 'm',
            'val'          => ['value' => 2],
        ];
    }
}
