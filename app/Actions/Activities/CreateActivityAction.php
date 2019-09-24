<?php

namespace App\Actions\Activities;

use App\Models\Activity;
use App\Models\Attribute;
use App\Models\AttributeValue;

class CreateActivityAction
{
    // TODO: What about wrapping this in a transaction? How expensive would this be?
    public function __invoke($data)
    {
        $activitiesData = collect($data)->except('experiment_id', 'attributes')->toArray();
        $activitiesData['owner_id'] = auth()->id();
        $activity = Activity::create($activitiesData);
        if (array_key_exists('experiment_id', $data)) {
            $activity->experiments()->attach($data['experiment_id']);
        }

        $activity->fresh();

        if (array_key_exists('attributes', $data)) {
            foreach ($data['attributes'] as $attribute) {
                $attr = Attribute::create([
                    'name'              => $attribute['name'],
                    'attributable_type' => Activity::class,
                    'attributable_id'   => $activity->id,
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

        return $activity;
    }
}