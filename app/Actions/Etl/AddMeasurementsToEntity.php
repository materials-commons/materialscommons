<?php

namespace App\Actions\Etl;

class AddMeasurementsToEntity
{
    public function __invoke($data)
    {
        foreach ($data['attributes'] as $attribute) {
            $attributeId = "";
            if (array_key_exists('id', $attribute)) {
                $attributeId = $attribute["id"];
            }

            if ($attributeId === "") {
                $this->addNewAttribute();
            } else {
                $this->updateExistingAttribute($attributeId);
            }
        }
    }

    private function addNewAttribute()
    {

    }

    private function updateExistingAttribute($attributeId)
    {

    }
}