<?php

namespace App\Actions\Migration;

use App\Models\Attribute;
use App\Models\AttributeValue;

class ImportEntityStateAttributeValues extends AbstractImporter
{
    use ItemLoader;

    private $measurement2attribute;
    private $bestmeasures;

    public function __construct($pathToDumpfiles, $ignoreExisting)
    {
        parent::__construct($pathToDumpfiles, "attribute_values", $ignoreExisting);
    }

    protected function setup()
    {
        $this->measurement2attribute = $this->loadItemMapping('property2measurement.json', 'measurement_id',
            'property_id');
        $this->bestmeasures = $this->loadItemMapping("best_measure_history.json", 'measurement_id', 'property_id');
    }

    protected function cleanup()
    {
        $this->measurement2attribute = [];
        $this->bestmeasures = null;
    }

    protected function loadData($data)
    {
        if (!isset($this->measurement2attribute[$data['id']])) {
            return null;
        }

        $attrUuid = $this->measurement2attribute[$data['id']];
        $attr = Attribute::where('uuid', $attrUuid)->first();
        if ($attr == null) {
            return null;
        }

        $av = AttributeValue::create([
            'uuid'         => $data['id'],
            'val'          => ['value' => $data['value'] ?? ""],
            'unit'         => $data['unit'] ?? "",
            'attribute_id' => $attr->id,
        ]);

        if (isset($this->bestmeasures[$data['id']])) {
            $attr->update(['best_value_id' => $av->id]);
        }

        return $av;
    }

    protected function getModelClass()
    {
        return AttributeValue::class;
    }

    protected function shouldLoadRelationshipsOnSkip()
    {
        return false;
    }

    protected function loadRelationships($item)
    {
    }
}