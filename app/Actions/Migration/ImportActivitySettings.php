<?php

namespace App\Actions\Migration;

use App\Models\Activity;
use App\Models\Attribute;
use App\Models\AttributeValue;

class ImportActivitySettings extends AbstractImporter
{
    use ItemLoader;
    use ItemCreater;

    private $setup2process;

    public function __construct($pathToDumpfiles, $ignoreExisting)
    {
        parent::__construct($pathToDumpfiles, "attributes", $ignoreExisting);
    }

    protected function setup()
    {
        $this->setup2process = $this->loadItemMapping('process2setup.json', 'setup_id', 'process_id');
    }

    protected function loadData($data)
    {
        if (!isset($data['setup_id'])) {
            // Some older entries don't have a setup_id field, ignore them
            return null;
        }

        $setupId = $data['setup_id'];
        if (!isset($this->setup2process[$setupId])) {
            return null;
        }
        $activityUuid = $this->setup2process[$setupId];
        $activity = ItemCache::findActivity($activityUuid);
        if ($activity == null) {
            return null;
        }

        $attrModelData = $this->createCommonModelData($data);
        if ($attrModelData == null) {
            return null;
        }

        $attrModelData['attributable_type'] = Activity::class;
        $attrModelData['attributable_id'] = $activity->id;
        $attr = Attribute::create($attrModelData);

        AttributeValue::create([
            'uuid'         => $data['id'],
            'val'          => ['value' => $data['value'] ?? ""],
            'unit'         => $data['unit'] ?? "",
            'attribute_id' => $attr->id,
        ]);

        return $attr;
    }

    protected function cleanup()
    {
        $this->setup2process = [];
    }

    protected function shouldLoadRelationshipsOnSkip()
    {
        return false;
    }

    protected function getModelClass()
    {
        return Attribute::class;
    }

    protected function loadRelationships($item)
    {
    }
}