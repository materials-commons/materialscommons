<?php

namespace App\Traits\EntityStates;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\EntityState;

trait AddEntityStateAttributes
{
    private function addSampleAttributes(EntityState $entityState, $attributes)
    {
        foreach ($attributes as $attribute) {
            $attr = Attribute::create([
                'name'              => $attribute['name'],
                'attributable_type' => EntityState::class,
                'attributable_id'   => $entityState->id,
                'eindex'            => array_key_exists('eindex', $attribute) ? $attribute['eindex'] : null,
            ]);

            $unit = '';
            if (array_key_exists('unit', $attribute)) {
                $unit = $attribute['unit'];
            }

            AttributeValue::create([
                'val'          => ['value' => $attribute['value']],
                'unit'         => $unit,
                'attribute_id' => $attr->id,
            ]);
        }
    }
}