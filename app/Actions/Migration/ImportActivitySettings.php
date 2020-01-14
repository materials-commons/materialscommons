<?php

namespace App\Actions\Migration;

use App\Models\Activity;
use App\Models\Attribute;
use App\Models\AttributeValue;

class ImportActivitySettings extends AbstractImporter
{
    use ItemLoader;

    protected function setup()
    {
        $this->setupItemMapping('process2setup.json', 'setup_id', 'process_id');
    }

    protected function loadData($data)
    {
        $setupId = $data['setup_id'];
        if (!isset($this->knownItems[$setupId])) {
            return null;
        }
        $activityUuid = $this->knownItems[$setupId];
        $activity = Activity::where('uuid', $activityUuid)->first();
        if ($activity == null) {
            return null;
        }
        $attrModelData = $this->createCommonModelData($data);
        $attrModelData['attributable_type'] = Activity::class;
        $attrModelData['attributable_id'] = $activity->id;
        echo "Creating attribute {$attrModelData['name']} for activity {$activity->name}\n";
        $attr = Attribute::create($attrModelData);
        AttributeValue::create([
            'val'          => ['value' => $data['value'] ?? ""],
            'unit'         => $data['unit'] ?? "",
            'attribute_id' => $attr->id,
        ]);
        return $attr;
    }

    protected function cleanup()
    {
        $this->cleanupItemMapping();
    }
}